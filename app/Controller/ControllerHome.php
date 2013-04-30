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
class ControllerHome extends ControllerBase
{
	/**
	 * Handler untuk GET/POST /home/index
	 */
	public function actionIndex() {
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Template configuration
		$this->layout = 'modules/home/index.tpl';
		$events = ModelBase::factory('Wakuwakuw', new \app\Parameter())->getMeetups();
		$data = ModelBase::factory('Template')->getHomeData(compact('events'));

		// Render
		return $this->render($data);
	}
}
