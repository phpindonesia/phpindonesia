<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Model\ModelBase;

/**
 * ControllerHome
 *
 * @author PHP Indonesia Dev
 */
class ControllerAuth extends ControllerBase
{
	/**
	 * Handler untuk GET/POST /auth/login
	 */
	public function actionLogin() {
		// Hanya untuk non-login user
		if ($this->request->getSession()->get('login') == true) {
			return $this->redirect('/home');
		}

		// Data
		$this->layout = 'modules/auth/login.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Masuk'));

		// Proses form jika POST terdeteksi
		if ($_POST) {
			$loginResult = ModelBase::factory('Auth')->login($_POST);

			// Cek hasil login
			if ($loginResult->get('success') === true) {
				// Login berhasil
				$this->request->getSession()->set('login', true);
				$this->request->getSession()->set('userId', $loginResult->get('data'));
				
				// Redirect ke home
				return $this->redirect('/home');
			}

			$data['result'] = $loginResult;
		}

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/logout
	 */
	public function actionLogout() {
		// Hanya untuk login user
		if ($this->request->getSession()->get('login') == false) {
			return $this->redirect('/auth/login');
		}

		// Proses permintaan logout
		$this->request->getSession()->set('login', false);

		return $this->redirect('/home');
	}

	/**
	 * Handler untuk GET/POST /auth/register
	 */
	public function actionRegister() {
		// Hanya untuk non-login user
		if ($this->request->getSession()->get('login') == true) {
			return $this->redirect('/home');
		}

		// Data
		$this->layout = 'modules/auth/register.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Daftar'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/forgot
	 */
	public function actionForgot() {
		// Hanya untuk non-login user
		if ($this->request->getSession()->get('login') == true) {
			return $this->redirect('/home');
		}
		
		// Data
		$this->layout = 'modules/auth/forgot.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Lupa Sandi'));

		// Render
		return $this->render($data);
	}
}
