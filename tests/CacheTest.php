<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\CacheBundle;
use app\CacheManager;

class CacheTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek konsistensi cache components instance
	 */
	public function testCekKonsistensiAppCacheComponents() {
		$cacheBundle = new CacheBundle();
		$cacheManager = new CacheManager();
		$this->assertInstanceOf('\app\CacheBundleInterface', $cacheBundle);
		$this->assertInstanceOf('\app\CacheManagerInterface', $cacheManager);
	}

	/**
	 * Cache bundle test
	 */
	public function testCekAppCacheBundle() {
		$cacheBundle = new CacheBundle('something fun');

		$this->assertEquals('something fun', $cacheBundle->dump());
	}

	/**
	 * Cache manager test
	 */
	public function testCekAppCacheManager() {
		$cacheManager = new CacheManager();

		$cacheKey = md5('secret');

		// Cek has method
		$this->assertFalse($cacheManager->has($cacheKey));

		// Cek set method
		$cacheManager->set($cacheKey, 'Something to stored',0);
		$cacheManager->set('otherkey', 'Something to stored',0);

		// Cek get method
		$this->assertEquals('Something to stored', $cacheManager->get($cacheKey));

		// Delete cache
		$cacheManager->remove($cacheKey);

		$this->assertFalse($cacheManager->has($cacheKey));

		// Clear all cache
		$cacheManager->clear();

		$this->assertFalse($cacheManager->has('otherkey'));
	}
}