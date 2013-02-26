<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerCommunity;
use Symfony\Component\HttpFoundation\Request;

class ControllerCommunityTest extends PHPUnit_Framework_TestCase {

	/**
	 * Set up
	 */
	public function setUp() {
		$_GET['page'] = '1';
		$_POST['query'] = 'tulis';

		// Setting Propel
		Propel::init(str_replace('app', 'conf', APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'connection.php');
	}

	/**
	 * Tear down
	 */
	public function tearDown() {
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
		$request = Request::create('/community/article');
		$controllerCommunity = new ControllerCommunity($request);
		$response = $controllerCommunity->actionArticle();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}
}