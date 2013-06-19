<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use app\Acl;
use app\AclDriverInterface;

/**
 * AclDriver
 *
 * @author PHP Indonesia Dev
 * @Annotations
 */
class AclDriver implements AclDriverInterface
{

	public $name;
	public $availableActions = array();
	public $config = array();


	/**
	 * Menentukan sebuah action dalam range atau tidak
	 *
	 * @param string Action
	 * @return bool
	 */
	public function inRange($action) {
		return in_array($action, $this->availableActions);
	}

	/**
	 * Getter config
	 *
	 * @param string $action
	 * @return array $config
	 */
	public function getConfig($action) {
		return isset($this->config[$action]) ? $this->config[$action] : array();
	}

	/**
	 * Permission checker
	 *
	 * @param int Permission type
	 * @param array Permission configuration
	 * @param string User role
	 * @return bool
	 */
	public function grantPermission($type = Acl::READ, $config = array(), $role = 'guest') {
		$allowedRole = $config[$type];
		$allowed = ($allowedRole == 'all' || strpos($allowedRole, $role) !== false);

		return $allowed;
	}
}