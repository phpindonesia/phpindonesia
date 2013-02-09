<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

<<<<<<< HEAD
use app\Model\Orm\PhpidNodeQuery;
=======
use app\Model\ModelBase;
>>>>>>> upstream/develop

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
		$data = ModelBase::factory('Template')->getHomeData();

		// Render
		return $this->render($data);
	}
<<<<<<< HEAD

	public function actionLalala()
	{
		$nodes = PhpidNodeQuery::create()->find();

		// foreach ($nodes as $node) {
		// 	echo $node->getTitle();
		// 	echo "<hr />";
		// }

		$data = array(
			'title' => 'PHP Indonesia - Bar',
			'content' => 'You are in Lalala ',
			'objects' => $nodes
		);

		return $this->render($data);
	}
}
=======
}
>>>>>>> upstream/develop
