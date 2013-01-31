<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

class SetupTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek folder app
	 */
	public function testCekFolderApplikasi()
	{
		$folderAppExists = is_dir(realpath(__DIR__.'/../app'));
		$this->assertTrue($folderAppExists);
	}

	/**
	 * Cek folder vendor
	 */
	public function testCekFolderVendor()
	{
		$folderVendorExists = is_dir(realpath(__DIR__.'/../vendor'));
		$this->assertTrue($folderVendorExists);
	}
}