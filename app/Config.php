<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use Symfony\Component\Yaml\Yaml;

/**
 * Application Config
 * @example Config::get('site.title')
 * @author PHP Indonesia Dev
 */
class Config
{
    /**
	 * Get a particular value back from the config array
	 * @param string $index The index to fetch in dot notation
	 * @return mixed
	 */
	public static function get($index) {
		$index = explode('.', $index);
		$configFile = ('./conf/'.$index[0].'.yml');
		$config = Yaml::parse($configFile);
		return self::getValue($index, $config);
	}
	/**
	 * Navigate through a config array looking for a particular index
	 * @param array $index The index sequence we are navigating down
	 * @param array $value The portion of the config array to process
	 * @return mixed
	 */
	private static function getValue($index, $value) {
		if(is_array($index) and
		   count($index)) {
			$current_index = array_shift($index);
		}
		if(is_array($index) and
		   count($index) and
		   is_array($value[$current_index]) and
		   count($value[$current_index])) {
			return self::getValue($index, $value[$current_index]);
		} else {
			return $value[$current_index];
		}
	}
}
