<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use app\CacheManagerInterface;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\PhpFileCache;

/**
 * Application Cache Manager
 *
 * @author PHP Indonesia Dev
 */
class CacheManager implements CacheManagerInterface
{
	protected $cacheProvider;

	/**
	 * Constructor
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		if (defined('STDIN')) {
			$this->cacheProvider = new ArrayCache();
		} elseif (extension_loaded('apc')) {
			$this->cacheProvider = new ApcCache();
		} else {
			$this->cacheProvider = new PhpFileCache(CACHE_PATH);
		}
	}

	/**
	 * Check cache
	 *
	 * @param string cache key
	 * @return bool
	 */
	public function has($key) {
		return $this->cacheProvider->contains($key);
	}

	/**
	 * Get cache
	 *
	 * @param string cache key
	 * @return mixed False when fail, string when success
	 */
	public function get($key) {
		return $this->cacheProvider->fetch($key);
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

		return $this->cacheProvider->save($key, $value, $lifetime);
	}

	/**
	 * Delete an item from cache
	 *
	 * @param   string   the key
	 * @return  boolean  TRUE on success, FALSE otherwise
	 */
	public function remove($key) {
		return $this->cacheProvider->delete($key);
	}

	/**
	 * Clear all cache
	 */
	public function clear() {
		return $this->cacheProvider->deleteAll();
	}
}