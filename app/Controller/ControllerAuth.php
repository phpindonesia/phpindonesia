<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Parameter;
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
		if ($this->acl->isLogin()) return $this->redirect('/home');

		// Data
		$this->layout = 'modules/auth/login.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Masuk'));

		// Proses form jika POST terdeteksi
		// @codeCoverageIgnoreStart
		if ($_POST) {
			$loginResult = ModelBase::factory('Auth')->login($_POST);

			// Cek hasil login
			if ($loginResult->get('success') === true) {
				// Login berhasil
				$this->session->set('login', true);
				$this->session->set('userId', $loginResult->get('data'));
				
				// Redirect ke after login url atau ke home
				return $this->redirect($this->session->get('redirectAfterLogin', '/home'));
			}

			$this->data->set('result', $loginResult);
		}
		// @codeCoverageIgnoreEnd

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/loginfb
	 */
	public function actionLoginfb() {
		// Hanya untuk non-login user
 		if ($this->acl->isLogin()) return $this->redirect('/home');

		// Beri flag
		$this->session->set('loginFacebook', true);

		return $this->redirect('/facebook');
	}

	/**
	 * Handler untuk GET/POST /auth/logout
	 */
	public function actionLogout() {
		// Proses permintaan logout
		$this->session->clear();

		return $this->redirect('/home');
	}

	/**
	 * Handler untuk GET/POST /auth/register
	 */
	public function actionRegister() {
		// Hanya untuk non-login user
 		if ($this->acl->isLogin()) return $this->redirect('/home');

		// Data
		$this->layout = 'modules/auth/register.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Daftar'));

		// Proses form jika POST terdeteksi
		// @codeCoverageIgnoreStart
		if ($_POST) {
			$registrationResult = ModelBase::factory('Auth')->register($_POST);

			// Cek hasil registrasi
			if ($registrationResult->get('success') === true) {
				// Login berhasil
				$this->session->set('login', true);
				$this->session->set('userId', $registrationResult->get('data'));
				
				// Redirect ke after login url atau ke home
				return $this->redirect($this->session->get('redirectAfterLogin', '/home'));
			}

			$this->data->set('result', $registrationResult);
		}
		// @codeCoverageIgnoreEnd

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/registerfb
	 */
	public function actionRegisterfb() {
		// Hanya untuk non-login user
 		if ($this->acl->isLogin()) return $this->redirect('/home');

		return $this->redirect('/facebook');
	}

	/**
	 * Handler untuk GET/POST /auth/forgot
	 */
	public function actionForgot() {
		// Hanya untuk non-login user
 		if ($this->acl->isLogin()) return $this->redirect('/home');

 		// Proses form jika POST terdeteksi
		// @codeCoverageIgnoreStart
		if ($_POST) {
			$resetResult = new Parameter(array('success' => false));

			if ( ! isset($_POST['email']) || empty($_POST['email'])) {
				$resetResult->set('error', 'Masukan email anda!');
			} else {
				$sent = ModelBase::factory('Auth')->sendReset($_POST['email']);

				if ($sent) {
					// Redirect ke halaman utama
					$message = 'Link terkirim, periksa email anda!';
					$alert = ModelBase::factory('Template')->render('blocks/alert/success.tpl', compact('message'));
					$this->setAlert('info', $alert ,2000,true);
					return $this->redirect('/home');
				} else {
					$resetResult->set('error', 'Email yang anda masukan belum terdaftar!');
				}
			}

			$this->data->set('result', $resetResult);
		}
		// @codeCoverageIgnoreEnd
		
		// Data
		$this->layout = 'modules/auth/forgot.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Lupa Sandi'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/reconfirmation
	 */
	public function actionReconfirmation() {
		// @codeCoverageIgnoreStart
		// Hanya untuk login user
 		if ($this->acl->isLogin()) {
			// Resend
			$currentUser = $this->data->get('user');
			ModelBase::factory('Auth')->sendConfirmation($currentUser->get('Uid'));
 		}
		// @codeCoverageIgnoreEnd

		// Render
		return $this->redirect('/home');
	}

	/**
	 * Handler untuk GET/POST /auth/confirmation
	 */
	public function actionConfirmation() {
		// Cari di GET
		$token = $this->data->get('getData[token]','',true);

		if (empty($token)) {
			throw new \InvalidArgumentException('Token konfirmasi tidak ditemukan!');
		}

		// @codeCoverageIgnoreStart
		// cek hasil konfirmasi
		$confirmationResult = ModelBase::factory('Auth')->confirm($token);

		if ($confirmationResult->get('success') == false) {
			throw new \InvalidArgumentException('Token konfirmasi tidak valid!');
		} else {
			$message = 'Konfirmasi akun anda berhasil';
			$alert = ModelBase::factory('Template')->render('blocks/alert/success.tpl', compact('message'));
			$this->setAlert('info', $alert,5000,true);

			// Login jika belum
			$this->session->set('login', true);
			$this->session->set('userId', $confirmationResult->get('data'));
		}

		// Render
		return $this->redirect('/home');
		// @codeCoverageIgnoreEnd
	}
}
