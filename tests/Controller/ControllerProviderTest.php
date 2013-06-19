<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Model\ModelBase;
use app\Controller\ControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class ControllerProviderTest extends PhpindonesiaTestCase {

	protected $needDatabase = true;

	public function before() {
		$this->createDummyArticle();
	}

	/**
	 * Cek konsistensi controller base instance
	 */
	public function testCekKonsistensiAppControllerProvider() {
		$request = Request::create('/provider/article');
		$controllerProvider = new ControllerProvider($request);

		$this->assertInstanceOf('\app\Controller\ControllerBase', $controllerProvider);
		$this->assertInstanceOf('\app\Controller\ControllerProvider', $controllerProvider);
		$this->assertObjectHasAttribute('request', $controllerProvider);
	}

	/**
	 * Cek action index
	 */
	public function testCekActionIndexAppControllerProvider() {
		// Setup fixture
		$article = ModelBase::ormFactory('PhpidNodeQuery')->findOneByTitle('Title');

		$request = Request::create('/provider/article');
		$controllerProvider = new ControllerProvider($request);
		$controllerProvider->getData()->set('postData',array(
			'id' => $article->getNid(),
			'title' => $article->getTitle(),
			'content' => 'Updated!',
		));

		$response = $controllerProvider->actionArticle();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}
}