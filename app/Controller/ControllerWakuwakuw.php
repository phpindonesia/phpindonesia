<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Model\ModelBase;
use app\Parameter;

/**
 * ControllerWakuwakuw
 *
 * @author PHP Indonesia Dev
 */
class ControllerWakuwakuw extends ControllerBase
{
	/**
	 * @var Token
	 */
	protected $currentUser, $wakuToken, $defaultParam;

	/**
	 * beforeAction Hook
	 */
	public function beforeAction() {
		parent::beforeAction();

		if ( ! $this->acl->isLogin()) return $this->redirect('/signin');

		$this->currentUser = ModelBase::factory('User')->getUser($this->session->get('userId'));
		$this->wakuToken = $this->currentUser->get('AdditionalData[wk_access_token]',null,true);
		$this->defaultParam = new Parameter(array(
			'redirectUrl' => 'http://dev.phpindonesia.net/wakuwakuw',
			'wakuwakuwToken' => $this->wakuToken,
		));
	}

	/**
	 * Handler untuk GET/POST /wakuwakuw/index
	 */
	public function actionIndex() {
		// Perform oAuth
		$accessToken = $this->wakuToken;
		$code = $this->data->get('getData[code]',NULL,true);
		$param = $this->defaultParam;

		$wakuUser = ModelBase::factory('Wakuwakuw', $param)->getUser();

		if ( ! $wakuUser && empty($code))
		{
			return $this->redirect(ModelBase::factory('Wakuwakuw', $param)->getLoginUrl());
		}
		elseif (!empty($code))
		{
			$accessToken = ModelBase::factory('Wakuwakuw', $param)->getAccessToken($code);

			if (empty($accessToken))
			{
				return $this->redirect(ModelBase::factory('Wakuwakuw', $param)->getLoginUrl());
			}

			$param->set('wakuwakuwToken', $accessToken);
			$wakuUser = ModelBase::factory('Wakuwakuw', $param)->getUser();
		}

		if (!$wakuUser) {
			throw new \Exception('Error processing oAuth', 1);
		}

		// Update user
		$wid = $wakuUser->get('id');
		$wk_access_token = $accessToken;
		ModelBase::factory('User')->updateUserData($this->session->get('userId'),compact('wk_access_token','wid'));

		// Clean up
		$after_waku = $this->session->get('after_waku');
		$this->session->remove('after_waku');

		if (empty($after_waku)) $after_waku = '/home';

		return $this->redirect($after_waku);
	}

	/**
	 * Handler untuk GET/POST /wakuwakuw/rsvp
	 */
	public function actionRsvp() {
		$event = $this->request->get('id');

		if ( ! $this->wakuToken) {
			$this->session->set('after_waku', '/wakuwakuw/rsvp/'.$event);

			return $this->redirect('/wakuwakuw');
		}

		$param = $this->defaultParam;
		$rsvp = ModelBase::factory('Wakuwakuw', $param)->rsvp($event);

		if ($rsvp) {
			// Success
			$this->setAlert('success', 'RSVP berhasil!', 0, true);
		} else {
			// Fail
			$this->setAlert('error', 'RSVP gagal!', 0, true);
		}

		return $this->redirect('/home');
	}
}