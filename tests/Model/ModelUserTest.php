<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Model\ModelBase;
use app\Model\ModelUser;

class ModelUserTest extends PHPUnit_Framework_TestCase {

	/**
	 * Set Up hook
	 */
	public function setUp() {
		// Setting Propel
		Propel::init(str_replace('app', 'conf', APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'connection.php');
	}

	/**
	 * Tear Down hook
	 */
	public function tearDown() {
		$this->deleteDummyUser();
	}

	/**
	 * Cek konsistensi model User instance
	 */
	public function testCekKonsistensiModelUser() {
		$user = ModelBase::factory('User');

		$this->assertInstanceOf('\app\Model\ModelBase', $user);
		$this->assertInstanceOf('\app\Model\ModelUser', $user);
	}

	/**
	 * Cek fetching data
	 */
	public function testCekGetAllUser() {
		$user = new ModelUser();

		// Invalid data (Data kosong)
		$allUsers = $user->getAllUser();

		$this->assertCount(0, $allUsers);

		// Test valid proses
		$this->createDummyUser();

		$allUsers = $user->getAllUser();

		$this->assertCount(1, $allUsers);
	}


	/**
	 * Create dummy user
	 */
	protected function createDummyUser() {
		$user = new ModelUser();
		$user->createUser('dummy', 'dummy@oot.com', 'secret');
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