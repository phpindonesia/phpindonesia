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

		return $this->redirect('/home/foo');
	}

	/**
	 * Handler untuk GET/POST /home/foo
	 *
	 * Tips : try access GET /home/foo/<numeric>
	 */
	public function actionFoo()
	{
		$id = $this->request->get('id');
		$data = array(
			'title' => 'PHP Indonesia - Bar',
			'content' => 'You are in Bar '.$id,
		);

		return $this->render($data);
	}
}