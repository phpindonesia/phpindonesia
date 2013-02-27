<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Parameter;
use app\Model\ModelBase;

class ModelAssetTest extends PhpindonesiaTestCase {

	protected $modelAsset;

	/**
	 * Setup
	 */
	public function before() {
		$file = 'undefined';
		$path = ASSET_PATH . DIRECTORY_SEPARATOR;
		$folder = '';

		$this->modelAsset = ModelBase::factory('Asset', new Parameter(compact('file','path','folder')));
	}

	/**
	 * Cek konsistensi model template instance
	 */
	public function testCekKonsistensiModelAsset() {
		$asset = $this->modelAsset;

		$this->assertInstanceOf('\app\Model\ModelBase', $asset);
		$this->assertInstanceOf('\app\Model\ModelAsset', $asset);
		$this->assertObjectHasAttribute('path', $asset);
		$this->assertObjectHasAttribute('assetFile', $asset);
		$this->assertObjectHasAttribute('assetModifiedTime', $asset);
		$this->assertObjectHasAttribute('filter', $asset);
		$this->assertObjectHasAttribute('subFolder', $asset);
	}

	/**
	 * Cek get data from cache
	 */
	public function testCekGetCollectionCacheVersionTemplate() {
		$asset = $this->modelAsset;
		$this->assertFalse($asset->getCollectionCacheVersion(array()));
	}
}