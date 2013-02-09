<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Controller\ControllerAsset;
use Symfony\Component\HttpFoundation\Request;

class ControllerAssetTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek action css
	 */
	public function testCekActionCssAppControllerAsset() {
		// Karena main.css sudah didefinisikan, ini akan mendapat response valid
		$request = Request::create('/asset/css', 'GET', array('id' => 'main.css'));
		$controllerAsset = new ControllerAsset($request);
		$response = $controllerAsset->actionCss();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testCekActionGagalControllerCssAsset() {
		$request = Request::create('/asset/css');
		$controllerAsset = new ControllerAsset($request);
		
		$this->setExpectedException(
			'Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException',
			'The file "'.ASSET_PATH.'/css/undefined" does not exist');


		$response = $controllerAsset->actionCss();
	}

	/**
	 * Cek action js
	 */
	public function testCekActionJsAppControllerAsset() {
		// Karena app.js sudah didefinisikan maka ini akan mendapat response yang valid
		$request = Request::create('/asset/js', 'GET', array('id' => 'app.js'));
		$controllerAsset = new ControllerAsset($request);
		$response = $controllerAsset->actionJs();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());

		// Karena bootstrap-alert.js adalah file yang valid maka ini akan mendapat response yang valid
		$request = Request::create('/asset/js', 'GET', array('id' => 'bootstrap-alert.js'));
		$controllerAsset = new ControllerAsset($request);
		$response = $controllerAsset->actionJs();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Cek action js gagal
	 */
	public function testCekActionJsGagalControllerAsset() {
		$request = Request::create('/aset/js');
		$controllerAsset = new ControllerAsset($request);

		$this->setExpectedException(
			'Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException',
			'The file "'.ASSET_PATH.'/js/undefined" does not exist');

		$response = $controllerAsset->actionJs();
	}

	/**
	 * Cek action image
	 */
	public function testCekActionImgAppControllerAsset() {
		// Satu level directory
		$request = Request::create('/asset/img', 'GET', array('id' => 'glyphicons-halflings-white.png'));
		$controllerAsset = new ControllerAsset($request);
		$response = $controllerAsset->actionImg();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testCekActionImgGagalAppControllerAsset() {

		$request = Request::create('/asset/img');
		$controllerAsset = new ControllerAsset($request);

		$this->setExpectedException(
			'Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException',
			'The file "'.ASSET_PATH.'/img/undefined" does not exist');

		$response = $controllerAsset->actionImg();
	}

	/**
	 * Cek action font
	 */
	public function testCekActionFontAppControllerAsset() {
		$request = Request::create('/asset/font', 'GET', array('id' => 'fontawesome-webfont.eot'));
		$controllerAsset = new ControllerAsset($request);
		$response = $controllerAsset->actionFont();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testCekActionFontGagalAppControllerAsset() {

		$request = Request::create('/asset/font');
		$controllerAsset = new ControllerAsset($request);

		$this->setExpectedException(
			'Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException',
			'The file "'.ASSET_PATH.'/font/undefined" does not exist');

		$response = $controllerAsset->actionFont();
	}

}