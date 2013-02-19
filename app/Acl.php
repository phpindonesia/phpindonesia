<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Application ACL
 *
 * @author PHP Indonesia Dev
 */
class Acl
{
	protected $session;

	/**
	 * Constructor.
	 *
	 * @param Session $session Current session instance
	 */
	public function __construct(Session $session) {
		$this->session = $session;
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
}