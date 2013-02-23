<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerBase;
use app\Session as AppSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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
	 * Cek set alert
	 */
	public function testCekSetAlertAppControllerBase() {
		// Test alert flow
		$session = new AppSession();

		$session->start();

		$sessionId = $session->getName();
		$cookies = array($sessionId => TRUE);

		$request = Request::create('/home/index', 'GET', array(), $cookies);

		if ( ! $request->hasPreviousSession()) {
			$request->setSession($session);
		} 

		$request->getSession()->set('alert', serialize(array(
			'alertType' => 'success',
			'alertMessage' => 'Something to say...',
		)));

		$controllerBase = new ControllerBase($request);

		// Cek data 
		$this->assertEquals('success', $controllerBase->getData()->get('alertType'));
		$this->assertEquals('Something to say...', $controllerBase->getData()->get('alertMessage'));

		// Set new alert for next request
		$controllerBase->setAlert('error', 'Some warning for next request...', 1000, true);

		// Cek data, seharusnya tidak berubah dari sebelumnya
		$this->assertEquals('success', $controllerBase->getData()->get('alertType'));
		$this->assertEquals('Something to say...', $controllerBase->getData()->get('alertMessage'));

		// Set new alert for current request
		$controllerBase->setAlert('error', 'Some warning...');

		// Cek data 
		$this->assertEquals('error', $controllerBase->getData()->get('alertType'));
		$this->assertEquals('Some warning...', $controllerBase->getData()->get('alertMessage'));
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