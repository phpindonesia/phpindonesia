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
        $request = Request::create('/Asset/css', 'GET', array('id' => 'main.css'));
        $controllerAsset = new ControllerAsset($request);
        $response = $controllerAsset->actionCss();

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCekActionGagalControllerCssAsset() {
        $request = Request::create('/Asset/css');
        $controllerAsset = new ControllerAsset($request);
        $response = $controllerAsset->actionCss();

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Cek action js
     */
    public function testCekActionJsAppControllerAsset() {
        $request = Request::create('/Asset/js', 'GET', array('id' => 'app.js'));
        $controllerAsset = new ControllerAsset($request);
        $response = $controllerAsset->actionJs();

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Cek action js gagal
     */
    public function testCekActionJsGagalControllerAsset() {
        $request = Request::create('/Asset/js');
        $controllerAsset = new ControllerAsset($request);
        $response = $controllerAsset->actionCss();

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Cek action image
     */
    public function testCekActionImgAppControllerAsset() {
        $request = Request::create('/Asset/img', 'GET', array('id' => 'glyphicons-halflings-white.png'));
        $controllerAsset = new ControllerAsset($request);
        $response = $controllerAsset->actionImg();

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCekActionImgGagalAppControllerAsset() {

        $request = Request::create('/Asset/img','GET',array('id'=>'no-immage'));
        $controllerAsset = new ControllerAsset($request);
        $response = $controllerAsset->actionCss();
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

}