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
		// Data
		$this->layout = 'modules/auth/login.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Masuk'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/register
	 */
	public function actionRegister() {
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
		// Data
		$this->layout = 'modules/auth/forgot.tpl';
		$data = ModelBase::factory('Template')->getAuthData(array('title' => 'Lupa Sandi'));

		// Render
		return $this->render($data);
	}
}
