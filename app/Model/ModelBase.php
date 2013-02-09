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
	const PREFIX = 'Model';
	const ORM = 'Orm';

	/**
	 * Factory method to manufactoring app\Models easier
	 *
	 * @param string $class Model Class Suffix ('Template' will be translated to 'ModelTemplate')
	 *
	 * @throws InvalidArgumentException if Model class doesn't exists
	 */
	public static function factory($class) {
		$class = __NAMESPACE__ . '\\' . self::PREFIX . $class;

		if ( ! class_exists($class)) {
			throw new \InvalidArgumentException('Model class not found');
		}

		return new $class();
	}

	/**
	 * Factory method to manufactoring ORM models easier
	 *
	 * @param string $class ORM Class
	 *
	 * @throws InvalidArgumentException if ORM class doesn't exists
	 */
	public static function ormFactory($class) {
		$ormClass = __NAMESPACE__ . '\\' . self::ORM . '\\' . $class;

		if ( ! class_exists($ormClass)) {
			throw new \InvalidArgumentException('ORM class not found');
		}

		return new $ormClass();
	}
}