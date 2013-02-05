<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

/**
 * ModelBase
 *
 * @author PHP Indonesia Dev
 */
class ModelBase 
{
	/**
	 * Factory method to manufactoring ORM models easier
	 *
	 * @param string $class ORM Class
	 *
	 * @throws InvalidArgumentException if ORM class doesn't exists
	 */
	public static function ormFactory($class)
	{
		$ormClass = __NAMESPACE__ . '\\Orm\\' . $class;

		if ( ! class_exists($ormClass)) {
			throw new \InvalidArgumentException('ORM class not found');
		}

		return new $ormClass();
	}
}