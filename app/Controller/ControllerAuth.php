<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

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
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Data
		$data = $this->data();
		$data['title'] = 'Masuk';

		// Render
		$this->layout = 'auth.login.tpl';
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/register
	 */
	public function actionRegister() {
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Data
		$data = $this->data();
		$data['title'] = 'Daftar';

		// Render
		$this->layout = 'auth.register.tpl';
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /auth/forgot
	 */
	public function actionForgot() {
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Data
		$data = $this->data();
		$data['title'] = 'Lupa Sandi';

		// Render
		$this->layout = 'auth.forgot.tpl';
		return $this->render($data);
	}

	/**
	 * Data
	 */
	protected function data() {
		// Data menu
		$data_menu	= array(
			'menu_top' => array(
				array('title' => 'Home', 'link' => '/'),
				array('title' => 'Masuk', 'link' => '/auth/login'),
				array('title' => 'Daftar', 'link' => '/auth/register'),
			),
			'menu_bottom' => array(),
		);

		// Data content
		$data_content = array(
			'title'		=> NULL,
			'content'	=> NULL,
		);

		// Merge data
		$data = array_merge($data_menu, $data_content);

		return $data;
	}
}
