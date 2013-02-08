<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerAuth;
use Symfony\Component\HttpFoundation\Request;

class ControllerAuthTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek action Login
	 */
	public function testCekActionLoginAppControllerAuth() {
		$request = Request::create('/auth/login');
		$controllerAuth = new ControllerAuth($request);
		$response = $controllerAuth->actionLogin();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Cek action Register
	 */
	public function testCekActionRegisterAppControllerAuth() {
		$request = Request::create('/auth/register');
		$controllerAuth = new ControllerAuth($request);
		$response = $controllerAuth->actionRegister();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Cek action Forgot
	 */
	public function testCekActionForgotAppControllerAuth() {
		$request = Request::create('/auth/forgot');
		$controllerAuth = new ControllerAuth($request);
		$response = $controllerAuth->actionForgot();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}
}