<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use Assetic\FilterManager;
use Assetic\Filter\LessphpFilter;
use Assetic\Factory\AssetFactory;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\AssetManager;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
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
	protected $subFolder = '';

	/**
	 * beforeAction Hook
	 */
	public function beforeAction() {
		$this->assetFile = $this->request->get('id', 'undefined');

		if (($subFolder = $this->request->get('subfolder')) && ! empty($subFolder)) {
			$this->subFolder = $subFolder . DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * Handler untuk GET /asset/css/somecss.css
	 */
	public function actionCss() {
		if ($this->assetFile == 'main.css') {
			// Build CSS from LESS
			$am = new AssetManager();
			$fm = new FilterManager();
			$fm->set('less', new LessphpFilter());
			$factory = new AssetFactory(ASSET_PATH);
			$factory->setFilterManager($fm);
			$factory->setAssetManager($am);
			$css = $factory->createAsset(
				array(
					'less/bootstrap.less',
					'less/custom.less',		// Custom style agar tidak merubah bootstrap default
					'less/responsive.less'	// Generate responsive
				), 
				array(
					'less', // filter through the filter manager's "scss" filter
				)
			);

			$file = $css->dump();
			$mime = 'text/css';
		} else {
			// @codeCoverageIgnoreStart
			// Validasi file
			list($file, $mime) = $this->getFileAttribute('css');
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
			$collection = new AssetCollection();
			$bootstrap = array(
				'js/jquery-1.9.1.min.js',
				'js/bootstrap-transition.js',
				'js/bootstrap-alert.js',
				'js/bootstrap-modal.js',
				'js/bootstrap-dropdown.js',
				'js/bootstrap-scrollspy.js',
				'js/bootstrap-tab.js',
				'js/bootstrap-tooltip.js',
				'js/bootstrap-popover.js',
				'js/bootstrap-button.js',
				'js/bootstrap-collapse.js',
				'js/bootstrap-carousel.js',
				'js/bootstrap-typeahead.js',
				'js/bootstrap-affix.js',
				'js/scripts.js' // Custom JS untuk manajemen keseluruhan JS lainnya
			);

			foreach ($bootstrap as $js) {
				$collection->add(new FileAsset(ASSET_PATH . DIRECTORY_SEPARATOR . $js));
			}

			$file = $collection->dump();
			$mime = 'application/javascript';
		} else {
			// Validasi file
			list($file,$mime) = $this->getFileAttribute('js');
		}

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Handler untuk /asset/img/someimage.png
	 */
	public function actionImg() {
		// Validasi
		list($file,$mime) = $this->getFileAttribute('img');

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Handler untuk /asset/font/somefont.ttf
	 */
	public function actionFont() {
		// Validasi
		list($file,$mime) = $this->getFileAttribute('font');

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
		// Default cache adalah 5 menit
		$age = 60*5;

		if ($asset instanceof File) {
			$content = file_get_contents($asset);
			$lastModified = new \DateTime(date('Y-m-d\TH:i:sP',$asset->getMTime()));
		} else {
			$content = $asset;
			$lastModified = new \DateTime();
		}

		// Prepare asset response
		$assetResponse = $this->render($content, 200, array('Content-Type' => $mime));
		$assetResponse->setLastModified($lastModified);
		$assetResponse->setMaxAge($age);

		return $assetResponse;
	}

	/**
	 * Generic method untuk mengambil nama file dan MIME
	 *
	 * @param string Asset type
	 *
	 * @return array Array berisi masing-masing nama dan MIME, ex : array('somefile.png', 'image/png');
	 */
	protected function getFileAttribute($type) {
		$file = $this->validateAssetFile($type, $this->assetFile);
		$mime = $file->getMimeType();

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
	protected function validateAssetFile($type, $fileName) {
		// Dapatkan path dari file
		return new File(ASSET_PATH . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $this->subFolder . $fileName, true);
	}

}