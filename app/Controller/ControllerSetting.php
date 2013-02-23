<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Model\ModelBase;

/**
 * ControllerSetting
 *
 * @author PHP Indonesia Dev
 */
class ControllerSetting extends ControllerBase
{
	public function beforeAction() {
		parent::beforeAction();

		// All setting actions only accessible from logged-in user
		if ( ! $this->acl->isLogin()) {
			throw new \BadMethodCallException('Anda harus login untuk melanjutkan!');
		}
	}

	/**
	 * Handler untuk GET/POST /setting/index
	 */
	public function actionIndex() {
		// Template configuration
		$this->layout = 'modules/setting/index.tpl';
		$data = ModelBase::factory('Template')->getSettingData();

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /setting/info
	 */
	public function actionInfo() {
		$content = ModelBase::factory('Setting')->handleInfo($this->data);

		if ($content->get('updated')) $this->setAlert('info', 'Informasi terupdate!');

		// Template configuration
		$this->layout = 'modules/setting/index.tpl';
		$data = ModelBase::factory('Template')->getSettingData(compact('content'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /setting/email
	 */
	public function actionMail() {
		$content = ModelBase::factory('Setting')->handleMail($this->data);

		if ($content->get('updated')) $this->setAlert('info', 'Email terupdate!');

		// Template configuration
		$this->layout = 'modules/setting/index.tpl';
		$data = ModelBase::factory('Template')->getSettingData(compact('content'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /setting/password
	 */
	public function actionPassword() {
		$content = ModelBase::factory('Setting')->handlePassword($this->data);

		if ($content->get('updated')) $this->setAlert('info', 'Password terupdate!');

		// Template configuration
		$this->layout = 'modules/setting/index.tpl';
		$data = ModelBase::factory('Template')->getSettingData(compact('content'));

		// Render
		return $this->render($data);
	}
}
