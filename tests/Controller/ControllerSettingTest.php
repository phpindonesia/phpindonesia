<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Session as AppSession;
use app\Model\ModelBase;
use app\Controller\ControllerSetting;
use Symfony\Component\HttpFoundation\Request;

class ControllerSettingTest extends PhpindonesiaTestCase {
	protected $needDatabase = true;
	protected $request;

	/**
	 * Set up
	 */
	public function before() {
		// Emulate logged in user
		$session = new AppSession();

		$session->start();

		$sessionId = $session->getName();
		$cookies = array($sessionId => TRUE);
		$request = Request::create('/setting', 'GET', array(), $cookies);

		if ( ! $request->hasPreviousSession()) {
			$request->setSession($session);
		} 

		$request->getSession()->set('login', true);
		$this->request = $request;
	}

	/**
	 * Cek konsistensi controller base instance
	 */
	public function testCekKonsistensiAppControllerSetting() {
		$controllerSetting = new ControllerSetting($this->request);

		$this->assertInstanceOf('\app\Controller\ControllerBase', $controllerSetting);
		$this->assertInstanceOf('\app\Controller\ControllerSetting', $controllerSetting);
		$this->assertObjectHasAttribute('request', $controllerSetting);
	}

	/**
	 * Cek action index
	 */
	public function testCekActionIndexAppControllerSetting() {
		$controllerSetting = new ControllerSetting($this->request);
		$response = $controllerSetting->actionIndex();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());

		// Fail on non-login user
		$request = Request::create('/setting');

		$this->setExpectedException('BadMethodCallException', 'Anda harus login untuk melanjutkan!');
		
		$controllerSetting = new ControllerSetting($request);
	}

	/**
	 * Cek action info
	 */
	public function testCekActionInfoAppControllerSetting() {
		$this->createDummyUser();
		$dummyUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByName('dummy');
		$dummyUserData = ModelBase::factory('User')->getUser($dummyUser->getUid());

		$controllerSetting = new ControllerSetting($this->request);
		$controllerSetting->getData()->set('user', $dummyUserData);
		$response = $controllerSetting->actionInfo();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Cek action mail
	 */
	public function testCekActionMailAppControllerSetting() {
		$this->createDummyUser();
		$dummyUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByName('dummy');
		$dummyUserData = ModelBase::factory('User')->getUser($dummyUser->getUid());

		$controllerSetting = new ControllerSetting($this->request);
		$controllerSetting->getData()->set('user', $dummyUserData);
		$response = $controllerSetting->actionMail();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Cek action password
	 */
	public function testCekActionPasswordAppControllerSetting() {
		$this->createDummyUser();
		$dummyUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByName('dummy');
		$dummyUserData = ModelBase::factory('User')->getUser($dummyUser->getUid());

		$controllerSetting = new ControllerSetting($this->request);
		$controllerSetting->getData()->set('user', $dummyUserData);
		$response = $controllerSetting->actionPassword();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}
}