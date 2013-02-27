<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Parameter;
use app\CacheBundle;
use app\Model\ModelBase;
use Assetic\Asset\AssetCollection;
use Symfony\Component\HttpFoundation\File\File;

/**
 * ControllerHome
 *
 * @author PHP Indonesia Dev
 */
class ControllerAsset extends ControllerBase 
{
	protected $assetFile;

	/**
	 * beforeAction Hook
	 */
	public function beforeAction() {
		$file = $this->request->get('id', 'undefined');
		$path = ASSET_PATH . DIRECTORY_SEPARATOR;
		$folder = '';

		if (($subFolder = $this->request->get('subfolder')) && ! empty($subFolder)) {
			$folder = $subFolder . DIRECTORY_SEPARATOR;
		}

		$this->modelAsset = ModelBase::factory('Asset', new Parameter(compact('file','path','folder')));
		$this->assetFile = $file;
	}

	/**
	 * Handler untuk GET /asset/css/somecss.css
	 */
	public function actionCss() {
		if ($this->assetFile == 'main.css') {
			// Build CSS from LESS
			$less = array(
				$this->modelAsset->setFile('less/variables.less'),
				$this->modelAsset->setFile('less/bootstrap.less'),
				$this->modelAsset->setFile('less/responsive.less'),		// Generate responsive
				$this->modelAsset->setFile('less/custom.style.less'),	// Custom style agar tidak merubah bootstrap default
			); 

			$file = $this->modelAsset->buildCollection($less,'less');
			$mime = 'text/css';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($less, $file);
		} elseif ($this->assetFile == 'code.css') {
			$codeCss = array(
				$this->modelAsset->setFile('css/codemirror.css'),
				$this->modelAsset->setFile('css/codemirror-monokai.css'),
			);

			$file = $this->modelAsset->buildCollection($codeCss,'css');
			$mime = 'text/css';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($codeCss, $file);
		} else {
			// @codeCoverageIgnoreStart
			// Validasi file
			list($file, $mime) = $this->modelAsset->getFileAttribute('css');
			// @codeCoverageIgnoreEnd
		}

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Handler untuk GET /asset/js/somejs.js
	 */
	public function actionJs() {
		if ($this->assetFile == 'app.js') {
			// Buatkan kompilasi Bootstrap JS
			$bootstrap = array(
				$this->modelAsset->setFile('js/jquery-1.9.1.min.js'),
				$this->modelAsset->setFile('js/bootstrap-transition.js'),
				$this->modelAsset->setFile('js/bootstrap-alert.js'),
				$this->modelAsset->setFile('js/bootstrap-modal.js'),
				$this->modelAsset->setFile('js/bootstrap-dropdown.js'),
				$this->modelAsset->setFile('js/bootstrap-scrollspy.js'),
				$this->modelAsset->setFile('js/bootstrap-tab.js'),
				$this->modelAsset->setFile('js/bootstrap-tooltip.js'),
				$this->modelAsset->setFile('js/bootstrap-popover.js'),
				$this->modelAsset->setFile('js/bootstrap-button.js'),
				$this->modelAsset->setFile('js/bootstrap-collapse.js'),
				$this->modelAsset->setFile('js/bootstrap-carousel.js'),
				$this->modelAsset->setFile('js/bootstrap-typeahead.js'),
				$this->modelAsset->setFile('js/bootstrap-affix.js'),
				$this->modelAsset->setFile('js/bootstrap-notify.js'),
				$this->modelAsset->setFile('js/scripts.js'), // Custom JS untuk manajemen keseluruhan JS lainnya
			);

			$file = $this->modelAsset->buildCollection($bootstrap, 'js');
			$mime = 'application/javascript';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($bootstrap, $file);
		} elseif ($this->assetFile == 'code.js') {
			$codeJs = array(
				$this->modelAsset->setFile('js/codemirror.js'),
				$this->modelAsset->setFile('js/codemirror-php.js'),
				//'js/codemirror-javascript.js',
			);

			$file = $this->modelAsset->buildCollection($codeJs, 'js');
			$mime = 'application/javascript';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($codeJs, $file);
		} else {
			// @codeCoverageIgnoreStart
			// Validasi file
			list($file,$mime) = $this->modelAsset->getFileAttribute('js');
			// @codeCoverageIgnoreEnd
		}

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Handler untuk /asset/img/someimage.png
	 */
	public function actionImg() {
		// Validasi
		list($file,$mime) = $this->modelAsset->getFileAttribute('img');

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Handler untuk /asset/font/somefont.ttf
	 */
	public function actionFont() {
		// Validasi
		list($file,$mime) = $this->modelAsset->getFileAttribute('font');

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Render method untuk Asset file
	 *
	 * @param string $mime MIME Type
	 * @param string $asset 
	 *
	 * @return Response
	 */
	protected function renderAsset($mime, $asset) {
		// Default content
		$content = '';
		// Default cache adalah 5 menit
		$age = 60*5;

		if ($asset instanceof File) {
			$content = file_get_contents($asset);
		} elseif ($asset instanceof CacheBundle || $asset instanceof AssetCollection) {
			$content = $asset->dump();
		}

		// Cache image for a month
		if (strpos($mime, 'image') !== false) {
			$age = 60*60*24*31;
		}

		// Handle modified time
		$lastModified = new \DateTime();
		$lastModified->setTimestamp($this->modelAsset->getLastModified());

		// Prepare asset response
		$assetResponse = $this->render($content, 200, array('Content-Type' => $mime));
		$assetResponse->setLastModified($lastModified);
		$assetResponse->setMaxAge($age);

		return $assetResponse;
	}
}