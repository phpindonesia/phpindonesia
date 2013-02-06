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

		$data = array(
			'title' => 'PHP Indonesia - Bersama Berkarya Berjaya',
			'content' => 'Portal PHP Indonesia sedang dalam pembangunan.',
		);

		return $this->render($data);
	}
}
