<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

/**
 * ControllerTemplate
 *
 * @author PHP Indonesia Dev
 */
class ControllerTemplate extends ControllerBase
{
	/**
	 * Handler untuk GET/POST /template/index
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
		$data['title']		= 'Tata Letak';
		$data['content']	= NULL;

		// Render
		$this->layout = 'modules/template/single.tpl';
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /template/left
	 */
	public function actionLeft()
	{
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Data
		$data['title']		= 'Tata Letak - Sisi Kiri';
		$data['content']	= NULL;

		// Render
		$this->layout = 'modules/template/left.tpl';
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /template/right
	 */
	public function actionRight()
	{
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Data
		$data['title']		= 'Tata Letak - Sisi Kanan';
		$data['content']	= NULL;

		// Render
		$this->layout = 'modules/template/right.tpl';
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /template/both
	 */
	public function actionBoth()
	{
		// @codeCoverageIgnoreStart
		// Exception untuk PHPUnit, yang secara otomatis selalu melakukan GET request ke / di akhir eksekusi
		if ($this->request->server->get('PHP_SELF', 'undefined') == 'vendor/bin/phpunit') {
			return $this->render('');
		}
		// @codeCoverageIgnoreEnd

		// Data
		$data['title']		= 'Tata Letak - Keduanya';
		$data['content']	= NULL;

		// Render
		$this->layout = 'modules/template/both.tpl';
		return $this->render($data);
	}
}
