<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class ControllerBaseTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek konsistensi controller base instance
	 */
	public function testCekKonsistensiAppControllerBase() {
		$request = Request::create('/home/index');
		$controllerBase = new ControllerBase($request);

		$this->assertInstanceOf('\app\Controller\ControllerBase', $controllerBase);
		$this->assertObjectHasAttribute('request', $controllerBase);

		// Cek beforeAction
		$controllerBase->getSession()->set('login', true);
		$controllerBase->getSession()->set('postData', array('foo' => 'bar'));
		$controllerBase->beforeAction();

		$this->assertArrayHasKey('foo', $controllerBase->getData()->get('postData'));
		$this->assertInstanceOf('\app\Acl', $controllerBase->getAcl());

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $controllerBase->handleException());
	}

	/**
	 * Cek redirect
	 */
	public function testCekRedirectAppControllerBase() {
		$request = Request::create('/home/index');
		$controllerBase = new ControllerBase($request);
		$response = $controllerBase->redirect('/controller/lain');

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $response);
		$this->assertEquals(302, $response->getStatusCode());
	}

	/**
	 * Cek render
	 */
	public function testCekRenderAppControllerBase() {
		$request = Request::create('/home/index');
		$controllerBase = new ControllerBase($request);
		$response = $controllerBase->render('Content');

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals('Content', $response->getContent());
	}
}