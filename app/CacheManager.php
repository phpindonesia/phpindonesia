<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use app\CacheManagerInterface;

/**
 * Application Cache Manager
 *
 * @author PHP Indonesia Dev
 */
class CacheManager implements CacheManagerInterface
{
	/**
	 * Check cache
	 *
	 * @param string cache key
	 * @return bool
	 */
	public function has($key) {
		return $this->get($key) !== false;
	}

	/**
	 * Get cache
	 *
	 * @param string cache key
	 * @return mixed False when fail, string when success
	 */
	public function get($key) {
		$value = apc_fetch($key, $success);

		return $success ? $value : false;
	}

	/**
	 * Set cache
	 *
	 * @param string cache key
	 * @param string cache value
	 * @param int cache lifetime
	 * @return bool 
	 */
	public function set($key, $value, $lifetime) {
		$lifetime = empty($lifetime) || !is_int($lifetime) ? 0 : $lifetime;

		apc_store($key, $value, $lifetime);
	}

	/**
	 * Delete an item from cache
	 *
	 * @param   string   the key
	 * @return  boolean  TRUE on success, FALSE otherwise
	 */
	public function remove($key) {
		return apc_delete($key);
	}

	/**
	 * Clear all cache
	 */
	public function clear() {
		$info = apc_cache_info('user');

		foreach ($info['cache_list'] as $obj) {
			apc_delete($obj['info']);
		}
	}
}