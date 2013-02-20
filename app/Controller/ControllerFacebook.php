<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use \Facebook as Facebook;
use app\Model\ModelBase;
use app\Parameter;

/**
 * ControllerFacebook
 *
 * @author PHP Indonesia Dev
 */
class ControllerFacebook extends ControllerBase
{
	protected $appId = '546853652015493';
	protected $secret = 'a1d06f877dc5f690d4df4b902128a402';
	protected $facebook;
	protected $scope;

	/**
	 * beforeAction Hook
	 */
	public function beforeAction() {
		$this->facebook = new Facebook(array(
		  'appId'  => $this->appId,
		  'secret' => $this->secret,
		));

		$this->scope = array('scope' => array(
			'email',
			'read_stream',
			'publish_stream',
			'offline_access',
			'user_about_me',
			'user_activities',
			'user_interests',
			'user_groups',
			'user_birthday',
		));
	}

	/**
	 * Handler untuk GET/POST /facebook/index
	 */
	public function actionIndex() {
		// Refresh state
		$this->facebook->destroySession();

		// Get User ID
		$user = $this->facebook->getUser();

		// Menentukan user state
		if ($user) {
			return $this->redirect('/facebook/update');
		} else {
			$loginUrl = $this->facebook->getLoginUrl($this->scope);
			return $this->redirect($loginUrl);
		}
	}

	/**
	 * Handler untuk GET/POST /facebook/update
	 */
	public function actionUpdate() {

		$user = $this->facebook->getUser();

		if ( ! $user) {
			$loginUrl = $this->facebook->getLoginUrl();
			return $this->redirect($loginUrl);
		}

		$fbUserData = $this->facebook->api('/me');

		if ($this->session->get('login') == false) {
			// Set flag di session
			$this->session->set('facebookData', $fbUserData);
			
			// Tentukan proses apakah mau login atau daftar
			$loginResult = false;

			if ($this->session->get('loginFacebook') == true) {
				$loginResult = ModelBase::factory('Auth')->loginFacebook($fbUserData, $this->facebook->getAccessToken());
			} 

			if ($loginResult instanceof Parameter && $loginResult->get('success') == true) {
				// User valid, proses authentifikasi berhasil
				$this->session->set('login', true);
				$this->session->set('userId', $loginResult->get('data'));

				return $this->redirect($this->session->get('redirectAfterLogin', '/home'));
			} else {
				// Persiapkan user untuk daftar
				$this->session->set('postData', $fbUserData);

				return $this->redirect('/auth/register');
			}

		} else {
			return $this->redirect($this->sessin->get('redirectAfterAuthenticated','/home'));
		}
	}
}