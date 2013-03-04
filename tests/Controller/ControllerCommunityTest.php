<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerCommunity;
use Symfony\Component\HttpFoundation\Request;

class ControllerCommunityTest extends PhpindonesiaTestCase {
	protected $needDatabase = true;

	/**
	 * Set up
	 */
	public function before() {
		$_GET['page'] = '1';
		$_POST['query'] = 'tulis';
	}

	/**
	 * Tear down
	 */
	public function after() {
		unset($_GET['page']);
		unset($_POST['query']);
	}

	/**
	 * Cek konsistensi controller base instance
	 */
	public function testCekKonsistensiAppControllerCommunity() {
		$request = Request::create('/community/index');
		$controllerCommunity = new ControllerCommunity($request);

		$this->assertInstanceOf('\app\Controller\ControllerBase', $controllerCommunity);
		$this->assertInstanceOf('\app\Controller\ControllerCommunity', $controllerCommunity);
		$this->assertObjectHasAttribute('request', $controllerCommunity);
	}

	/**
	 * Cek action index
	 */
	public function testCekActionIndexAppControllerCommunity() {
		$request = Request::create('/community/index');
		$controllerCommunity = new ControllerCommunity($request);
		$response = $controllerCommunity->actionIndex();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Cek action index
	 */
	public function testCekActionArticleAppControllerCommunity() {
		// Article list
		$request = Request::create('/community/article');
		$controllerCommunity = new ControllerCommunity($request);
		$response = $controllerCommunity->actionArticle();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());

		// Article detail exists
		$dummyArticle = $this->createDummyArticle();
		$request = Request::create('/community/article', 'GET', array('id' => $dummyArticle->getNid()));
		$controllerCommunity = new ControllerCommunity($request);
		$response = $controllerCommunity->actionArticle();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());

		// Article detail non exists
		$request = Request::create('/community/article', 'GET', array('id' => 9999999));
		$controllerCommunity = new ControllerCommunity($request);

		$this->setExpectedException('RuntimeException','Tulisan tidak dapat ditemukan');
		$controllerCommunity->actionArticle();
	}
}