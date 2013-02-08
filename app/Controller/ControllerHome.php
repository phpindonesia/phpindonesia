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
class ControllerHome extends ControllerBase
{
	/**
	 * Handler untuk GET/POST /home/index
	 */
	public function actionIndex()
	{
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Data
		$data = $this->data();
		$data['title']		= 'Home';
		$data['content']	= 'Portal PHP Indonesia sedang dalam pembangunan.';

		// Render
		$this->layout = 'home.tpl';
		return $this->render($data);
	}

	protected function data()
	{
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
