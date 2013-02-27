<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Parameter;
use app\CacheManager;
use app\CacheBundle;
use Assetic\FilterManager;
use Assetic\Filter\LessphpFilter;
use Assetic\Filter\Yui;
use Assetic\Factory\AssetFactory;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\AssetManager;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\AssetCollection;
use Symfony\Component\HttpFoundation\File\File;

/**
 * ModelMailer
 *
 * @author PHP Indonesia Dev
 */
class ModelAsset extends ModelBase 
{
	protected $path;
	protected $assetFile;
	protected $assetModifiedTime;
	protected $filter = array();
	protected $subFolder = '';

	/**
	 * Constructor
	 */
	public function __construct(Parameter $parameter) {
		// Initialize all parameter
		$this->path = $parameter->get('path', '');
		$this->assetFile = $parameter->get('file', '');
		$this->assetModifiedTime = $parameter->get('assetModifiedTime', 0);
		$this->subFolder = $parameter->get('folder', '');

		// Build filter
		$this->filter = array(
			'less' => new LessphpFilter(),
			'css'=> new Yui\CssCompressorFilter($this->path . 'yuicompressor.jar'),
			'js' => new Yui\JsCompressorFilter($this->path . 'yuicompressor.jar'),
		);
	}

	/**
	 * Mengambil max asset modified time
	 */
	public function getLastModified() {
		return $this->assetModifiedTime;
	}

	/**
	 * Generic method untuk mengambil nama file dan MIME
	 *
	 * @param string Asset type
	 *
	 * @return array Array berisi masing-masing nama dan MIME, ex : array('somefile.png', 'image/png');
	 */
	public function getFileAttribute($type) {
		$file = $this->validateAssetFile($type, $this->assetFile);
		$mime = $file->getMimeType();

		// Set last modified property
		$this->assetModifiedTime = $file->getMTime();

		return array($file, $mime);
	}

	/**
	 * Validasi ID dan existensi file
	 *
	 * @param  string $type [js|css|img]
	 * @param  string $fileName Nama file
	 *
	 * @return string $file Path
	 *
	 * @return InvalidArgumentException kalau file tidak ditemukan
	 */
	public function validateAssetFile($type, $fileName) {
		// Dapatkan path dari file
		return new File($this->path . $type . DIRECTORY_SEPARATOR . $this->subFolder . $fileName, true);
	}

	/**
	 * Provider for asset path
	 */
	public function setFile($asset) {
		return new FileAsset($this->path . $asset);
	}

	/**
	 * Set for cache version
	 *
	 * @param array file collection
	 * @param string file dump
	 * @return bool
	 */
	public function setCollectionCacheVersion($assets = array(), $assetDump = '') {
		$cacheKey = md5(serialize($assets).'*'.$this->assetModifiedTime);
		$cacheManager = new CacheManager();

		return $cacheManager->set($cacheKey, $assetDump, (60*60*24*31));
	}

	/**
	 * Get for cache version
	 *
	 * @param array file collection
	 * @return bool
	 */
	public function getCollectionCacheVersion($assets = array()) {
		$cacheKey = md5(serialize($assets).'*'.$this->assetModifiedTime);
		$cacheManager = new CacheManager();

		return $cacheManager->get($cacheKey);
	}

	/**
	 * Check for cache version
	 *
	 * @param array file collection
	 * @return bool
	 */
	public function checkCollectionCacheVersion($assets = array()) {
		// Check max last modified time 
		$lastModified = 0;
		foreach ($assets as $asset) {
			if ($lastModified < $asset->getLastModified()) {
				$lastModified = $asset->getLastModified();
			}
		}

		$this->assetModifiedTime = $lastModified;

		$cacheKey = md5(serialize($assets).'*'.$lastModified);
		$cacheManager = new CacheManager();

		return $cacheManager->has($cacheKey);
	}

	/**
	 * Provider for asset collection
	 */
	public function buildCollection($assets = array(), $type = 'js') {
		// Only process if necessary
		if ($this->checkCollectionCacheVersion($assets)) {
			return new CacheBundle($this->getCollectionCacheVersion($assets));
		}

		// Must in range
		if (in_array($type,array('js','css','less'))) {
			$filters = array();

			// Load the filters
			switch ($type) {
				case 'less':
					$filters = array($this->filter['less'], $this->filter['css']);
					break;
				
				case 'css':
					$filters = array($this->filter['css']);
					break;

				case 'js':
					$filters = array($this->filter['js']);
					break;
			}

			$collection = new AssetCollection($assets, $filters);
			$this->assetModifiedTime = $collection->getLastModified();
		}

		return !isset($collection) ? new CacheBundle() : $collection;
	}
}