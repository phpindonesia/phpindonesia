<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerUser;
use app\Model\ModelBase;
use Symfony\Component\HttpFoundation\Request;

class ControllerUserTest extends PHPUnit_Framework_TestCase {

	/**
	 * Set up
	 */
	public function setUp() {
		$_GET['page'] = '1';
		$_POST['query'] = 'facebook';

		// Setting Propel
		Propel::init(str_replace('app', 'conf', APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'connection.php');
	}

	/**
	 * Tear down
	 */
	public function tearDown() {
		unset($_GET['page']);
		unset($_POST['query']);

		$this->deleteDummyUser();
	}

	/**
	 * Cek konsistensi controller user instance
	 */
	public function testCekKonsistensiAppControllerUser() {
		$request = Request::create('/user');
		$controllerUser = new ControllerUser($request);

		$this->assertInstanceOf('\app\Controller\ControllerBase', $controllerUser);
		$this->assertInstanceOf('\app\Controller\ControllerUser', $controllerUser);
	}

	/**
	 * Cek action index
	 */
	public function testCekActionIndexAppControllerUser() {
		$request = Request::create('/user');
		$request->server->set('PATH_INFO', '/user');
		$controllerUser = new ControllerUser($request);
		$response = $controllerUser->actionIndex();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Cek action profile
	 */
	public function testCekActionProfileAppControllerUser() {
		$user = $this->createDummyUser();

		$request = Request::create('/user', 'GET', array('id' => $user->getUid()));
		$controllerUser = new ControllerUser($request);
		$response = $controllerUser->actionProfile();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Create dummy user
	 */
	protected function createDummyUser() {
		$auth = ModelBase::factory('Auth');
		return $auth->createUser('dummy', 'dummy@oot.com', 'secret');
	}

	/**
	 * Delete dummy user
	 */
	protected function deleteDummyUser() {
		if (($dummyUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByName('dummy')) && ! empty($dummyUser)) {
			$dummyUser->delete();
		}
	}
}