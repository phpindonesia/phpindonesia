<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use \BaseFacebook as BaseFacebookSdk;
use \Facebook as FacebookSdk;
use Symfony\Component\HttpFoundation\Request;

/**
 * Application Facebook
 *
 * @author PHP Indonesia Dev
 */
class Facebook extends FacebookSdk
{
	protected $request;
	protected $session;

	/**
	 * Constructor.
	 *
	 * @param Request $request
	 * @param array   $config
	 */
	public function __construct(Request $request, $config = array()) {
		$this->request = $request;
		$this->session = $this->request->getSession();

		BaseFacebookSdk::__construct($config);

		if (!empty($config['sharedSession'])) $this->initSharedSession();
	}

	/**
	 * Provides the implementations of the inherited abstract
	 * methods.  The implementation uses PHP sessions to maintain
	 * a store for authorization codes, user ids, CSRF states, and
	 * access tokens.
	 */
	protected function setPersistentData($key, $value) {
		$this->validateSupportedKeys($key, __METHOD__);

		$session_var_name = $this->constructSessionVariableName($key);
		$this->session->set($session_var_name, $value);
	}

	protected function getPersistentData($key, $default = false) {
		$this->validateSupportedKeys($key, __METHOD__);

		$session_var_name = $this->constructSessionVariableName($key);

		return $this->session->get($session_var_name, $default);
	}

	protected function clearPersistentData($key) {
		$this->validateSupportedKeys($key, __METHOD__);

		$session_var_name = $this->constructSessionVariableName($key);
		$this->session->set($session_var_name, NULL);
	}

	/**
	 * Validate the key before operation
	 * 
	 * @codeCoverageIgnore
	 */
	protected function validateSupportedKeys($key, $method) {
		if (!in_array($key, self::$kSupportedKeys)) {
			throw new \InvalidArgumentException('Unsupported key passed to '.$method.'.');
		}
	}
}