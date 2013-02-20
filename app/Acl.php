<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use Symfony\Component\HttpFoundation\Request;

/**
 * Application ACL
 *
 * @author PHP Indonesia Dev
 */
class Acl
{
	protected $request;
	protected $session;

	/**
	 * Constructor.
	 *
	 * @param Request $request Current request instance
	 */
	public function __construct(Request $request) {
		$this->request = $request;
		$this->session = $this->request->getSession();
	}

	/**
	 * isLogin
	 *
	 * Mengecek apakah user sedang login
	 */
	public function isLogin() {
		if (empty($this->session)) return false;

		return $this->session->get('login', false);
	}

	/**
	 * isContainFacebookData
	 *
	 * Mengecek apakah user sedang login dengan FB
	 */
	public function isContainFacebookData() {
		if (empty($this->session)) return false;

		return is_array($this->session->get('facebookData'));
	}
}