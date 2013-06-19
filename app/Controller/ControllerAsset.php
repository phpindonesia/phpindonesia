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
		if ($this->assetFile == 'main.css' || $this->assetFile == 'editor.css') {
			// Build CSS from LESS
			$less = ($this->assetFile == 'main.css') ? array(
				$this->modelAsset->setFile('less/variables.less'),
				$this->modelAsset->setFile('less/bootstrap.less'),
				$this->modelAsset->setFile('less/responsive.less'),		// Generate responsive
				$this->modelAsset->setFile('less/custom.style.less'),	// Custom style agar tidak merubah bootstrap default
			) : array(
				$this->modelAsset->setFile('less/bootstrap-markdown.less'),
			); 

			$file = $this->modelAsset->buildCollection($less,'less');
			$mime = 'text/css';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($less, $file->dump());
		} elseif ($this->assetFile == 'code.css') {
			$codeCss = array(
				$this->modelAsset->setFile('css/codemirror.css'),
				$this->modelAsset->setFile('css/codemirror-monokai.css'),
			);

			$file = $this->modelAsset->buildCollection($codeCss,'css');
			$mime = 'text/css';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($codeCss, $file->dump());
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
			$this->modelAsset->setCollectionCacheVersion($bootstrap, $file->dump());
		} elseif ($this->assetFile == 'editor.js') {
			$editorJs = array(
				$this->modelAsset->setFile('js/markdown.js'),
				$this->modelAsset->setFile('js/to-markdown.js'),
				$this->modelAsset->setFile('js/bootstrap-markdown.js'),
			);

			$file = $this->modelAsset->buildCollection($editorJs, 'js');
			$mime = 'application/javascript';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($editorJs, $file->dump());
		} elseif ($this->assetFile == 'code.js') {
			$codeJs = array(
				$this->modelAsset->setFile('js/codemirror.js'),
				$this->modelAsset->setFile('js/codemirror-php.js'),
				//'js/codemirror-javascript.js',
			);

			$file = $this->modelAsset->buildCollection($codeJs, 'js');
			$mime = 'application/javascript';

			// Set the cache version
			$this->modelAsset->setCollectionCacheVersion($codeJs, $file->dump());
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
	 * @param string $content 
	 *
	 * @return Response
	 */
	protected function renderAsset($mime, $content) {
		// Default cache adalah 5 menit
		$age = 60*5;

		// Handle modified time and Etag
		$lastModified = new \DateTime();
		$lastModified->setTimestamp($this->modelAsset->getLastModified());
		$etag = '"'.md5($this->assetFile.''.$this->modelAsset->getLastModified()).'"';

		// Check for request parameters
		// @codeCoverageIgnoreStart
		if($this->request->server->get('HTTP_IF_MODIFIED_SINCE') || $this->request->server->get('HTTP_IF_NONE_MATCH')) {
			$reqLastModified = $this->request->server->get('HTTP_IF_MODIFIED_SINCE');
			$reqEtag = $this->request->server->get('HTTP_IF_NONE_MATCH');

			$lastDate = clone $lastModified;
			$lastDate->setTimezone(new \DateTimeZone('UTC'));
			$lastModifiedDate = $lastDate->format('D, d M Y H:i:s').' GMT';

			if ($etag == $reqEtag || $lastModifiedDate == $reqLastModified) {
				// Not changes from last response
				return $this->notModified();
			}
		}
		// @codeCoverageIgnoreEnd

		// Generate asset
		if ($content instanceof File) {
                        ob_start();
                        readfile($content);
                        $content = ob_get_contents();
                        ob_end_clean();
                        ob_clean();
                } elseif ($content instanceof CacheBundle || $content instanceof AssetCollection) {
			$content = $content->dump();
                        ob_clean();
		}

		// Cache image for a month
		if (strpos($mime, 'image') !== false) {
			$age = 60*60*24*31;
		}

		// Prepare asset response
		$assetResponse = $this->render($content, 200, array('Content-Type' => $mime));
		$assetResponse->setLastModified($lastModified);
		$assetResponse->setMaxAge($age);
		$assetResponse->setEtag($etag);

		return $assetResponse;
	}
}