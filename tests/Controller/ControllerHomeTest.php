<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerHome;
use Symfony\Component\HttpFoundation\Request;

class ControllerHomeTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek konsistensi controller base instance
	 */
	public function testCekKonsistensiAppControllerHome()
	{
		$request = Request::create('/home/index');
		$controllerHome = new ControllerHome($request);

		$this->assertInstanceOf('\app\Controller\ControllerBase', $controllerHome);
		$this->assertInstanceOf('\app\Controller\ControllerHome', $controllerHome);
		$this->assertObjectHasAttribute('request', $controllerHome);
	}

	/**
	 * Cek action index
	 */
	public function testCekActionIndexAppControllerHome()
	{
		$request = Request::create('/home/index');
		$controllerHome = new ControllerHome($request);
		$response = $controllerHome->actionIndex();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $response);
		$this->assertEquals(302, $response->getStatusCode());
	}

	/**
	 * Cek action foo
	 */
	public function testCekActionFooAppControllerHome()
	{
		$request = Request::create('/home/index');
		$controllerHome = new ControllerHome($request);
		$response = $controllerHome->actionFoo();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}
}