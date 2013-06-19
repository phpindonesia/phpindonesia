<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use app\Acl;

/**
 * AclDriver Interface
 *
 * @author PHP Indonesia Dev
 */
interface AclDriverInterface
{
	/**
	 * Menentukan sebuah action dalam range atau tidak
	 *
	 * @param string Action
	 * @return bool
	 */
	public function inRange($action);

	/**
	 * Getter config
	 *
	 * @param string $action
	 * @return array $config
	 */
	public function getConfig($action);

	/**
	 * Permission checker
	 *
	 * @param int Permission type
	 * @param array Permission configuration
	 * @param string User role
	 * @return bool
	 */
	public function grantPermission($type = Acl::READ, $config = array(), $role = '');
}