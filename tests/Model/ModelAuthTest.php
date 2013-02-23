<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Model\ModelBase;
use app\Model\ModelAuth;

class ModelAuthTest extends PHPUnit_Framework_TestCase {

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
	 * Cek konsistensi model Auth instance
	 */
	public function testCekKonsistensiModelAuth() {
		$auth = ModelBase::factory('Auth');

		$this->assertInstanceOf('\app\Model\ModelBase', $auth);
		$this->assertInstanceOf('\app\Model\ModelAuth', $auth);

		$this->assertEquals(55, ModelAuth::HASH_LENGTH);
		$this->assertEquals(15, ModelAuth::HASH_COUNT);
		$this->assertEquals(7, ModelAuth::MIN_HASH_COUNT);
		$this->assertEquals(30, ModelAuth::MAX_HASH_COUNT);
		$this->assertEquals('./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', ModelAuth::ALNUM);
	}

	/**
	 * Cek Register
	 */
	public function testCekRegisterModelAuth() {
		$auth = new ModelAuth();

		// Invalid data
		$data = array();
		$hasilRegister = $auth->register($data);

		$this->assertFalse($hasilRegister->get('success'));
		$this->assertEquals('Isi username,email dan password!', $hasilRegister->get('error'));

		// Password tidak cocok
		$data = array('username' => 'foo', 'email' => 'foo@bar.com','password' => 'aman', 'cpassword' => 'tidak aman');
		$hasilRegister = $auth->register($data);

		$this->assertFalse($hasilRegister->get('success'));
		$this->assertEquals('Password tidak sama!', $hasilRegister->get('error'));

		// Test exists username/email
		$this->createDummyUser();

		$data = array('username' => 'dummy', 'email' => 'valid@oot.com', 'password' => 'secret', 'cpassword' => 'secret');
		$hasilRegister = $auth->register($data);

		$this->assertFalse($hasilRegister->get('success'));
		$this->assertEquals('Username sudah terdaftar!', $hasilRegister->get('error'));

		$data = array('username' => 'valid', 'email' => 'dummy@oot.com', 'password' => 'secret', 'cpassword' => 'secret');
		$hasilRegister = $auth->register($data);

		$this->assertFalse($hasilRegister->get('success'));
		$this->assertEquals('Email sudah terdaftar!', $hasilRegister->get('error'));

		// Test valid proses
		$this->deleteDummyUser();

		$data = array('username' => 'dummy', 'email' => 'frei.denken@facebook.com', 'password' => 'secret', 'cpassword' => 'secret');
		$hasilRegister = $auth->register($data);

		$this->assertTrue($hasilRegister->get('success'));

		// Cek konfirmasi
		$this->assertFalse($auth->isConfirmed($hasilRegister->get('data')));
	}

	/**
	 * Cek Login
	 */
	public function testCekLoginModelAuth() {
		$auth = new ModelAuth();

		// Invalid data
		$data = array();
		$hasilLogin = $auth->login($data);

		$this->assertFalse($hasilLogin->get('success'));
		$this->assertEquals('Isi username/email dan password!', $hasilLogin->get('error'));

		// Belum terdaftar
		$data = array('username' => 'undefined', 'password' => 'tidakvalid');
		$hasilLogin = $auth->login($data);

		$this->assertFalse($hasilLogin->get('success'));
		$this->assertEquals('Username/email yang anda masukkan belum terdaftar!', $hasilLogin->get('error'));

		// Username/email valid, tapi password tidak cocok
		$this->createDummyUser();

		$data = array('username' => 'dummy', 'password' => 'oot');
		$hasilLogin = $auth->login($data);

		$this->assertFalse($hasilLogin->get('success'));
		$this->assertEquals('Password yang anda masukkan tidak cocok!', $hasilLogin->get('error'));

		// Valid user
		$data = array('username' => 'dummy', 'password' => 'secret');
		$hasilLogin = $auth->login($data);

		$this->assertTrue($hasilLogin->get('success'));
		$this->assertInstanceOf('\app\Parameter', $auth->getUser($hasilLogin->get('data')));
	}

	/**
	 * Cek Login via FB
	 */
	public function testCekLoginFacebookModelAuth() {
		$auth = new ModelAuth();
		$token = 'SomeToken';

		// Invalid data
		$data = array();
		$hasilLoginFacebook = $auth->loginFacebook($data,$token);

		$this->assertFalse($hasilLoginFacebook->get('success'));

		// Valid user
		$this->createDummyUser();

		$data = array('username' => 'dummy', 'email' => 'dummy@oot.com', 'id' => 123);
		$hasilLoginFacebook = $auth->loginFacebook($data, $token);

		$this->assertTrue($hasilLoginFacebook->get('success'));
	}

	/**
	 * Cek update user
	 */
	public function testCekUpdateUserModelAuth() {
		$auth = new ModelAuth();

		$this->assertFalse($auth->updateUserData(NULL, array()));
		$this->assertFalse($auth->updateUserData(010101010, array()));

		// Valid update
		$this->createDummyUser();
		$dummyUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByName('dummy');

		$this->assertTrue($auth->updateUserData($dummyUser->getUid(), array('realname' => 'Dummy User')));
	}

	/**
	 * Cek konfirmasi
	 */
	public function testCekConfirmModelAuth() {
		$auth = ModelBase::factory('Auth');

		$this->createDummyUser();
		$dummyUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByName('dummy');
		$dummyUserUid = $dummyUser->getUid();
		$dummyUserToken = $dummyUser->getPass();

		// Cek sebelum konfirmasi
		$this->assertFalse($auth->isConfirmed($dummyUserUid));

		// Do confirm
		$auth->confirm($dummyUserToken);

		// Cek setelah konfirmasi
		$this->assertTrue($auth->isConfirmed($dummyUserUid));
	}

	/**
	 * Create dummy user
	 */
	protected function createDummyUser() {
		$auth = new ModelAuth();
		$auth->createUser('dummy', 'dummy@oot.com', 'secret');
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