<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerFacebook;
use Symfony\Component\HttpFoundation\Request;

class ControllerFacebookTest extends PhpindonesiaTestCase {

	/**
	 * Set up
	 */
	public function before() {
		$_SERVER['HTTP_HOST'] = 'dev.phpindonesia.net';
		$_SERVER['REQUEST_URI'] = '/facebook';
	}

	/**
	 * Tear down
	 */
	public function after() {
		unset($_SERVER['HTTP_HOST']);
		unset($_SERVER['REQUEST_URI']);
	}

	/**
	 * Cek konsistensi controller facebook instance
	 */
	public function testCekKonsistensiAppControllerFacebook() {
		$request = Request::create('/facebook');
		$controllerFacebook = new ControllerFacebook($request);

		$this->assertInstanceOf('\app\Controller\ControllerBase', $controllerFacebook);
		$this->assertInstanceOf('\app\Controller\ControllerFacebook', $controllerFacebook);
		$this->assertObjectHasAttribute('facebook', $controllerFacebook);
		$this->assertObjectHasAttribute('scope', $controllerFacebook);
	}

	/**
	 * Cek action index
	 */
	public function testCekActionIndexAppControllerFacebook() {
		$request = Request::create('/facebook');
		$controllerFacebook = new ControllerFacebook($request);
		$response = $controllerFacebook->actionIndex();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $response);
		$this->assertEquals(302, $response->getStatusCode());
	}

	/**
	 * Cek action update
	 */
	public function testCekActionUpdateAppControllerFacebook() {
		$request = Request::create('/facebook/update');
		$controllerFacebook = new ControllerFacebook($request);
		$response = $controllerFacebook->actionUpdate();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $response);
		$this->assertEquals(302, $response->getStatusCode());
	}
}