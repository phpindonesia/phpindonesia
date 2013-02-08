<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Route;
use app\ControllerKernel;
use Symfony\Component\HttpFoundation\Request;

class ControllerKernelTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek konsistensi controller kernel instance
	 */
	public function testCekKonsistensiAppControllerKernel() {
		$controllerKernel = new ControllerKernel(array(
			Route::HANDLER_CLASS => 'home',
			Route::HANDLER_ACTION => 'index',
		));

		$this->assertInstanceOf('\app\ControllerKernelInterface', $controllerKernel);
	}

	/**
	 * Cek invalid handler [KOSONG]
	 */
	public function testCekInvalidHandlerKosong() {
		$this->setExpectedException('Exception', 'Invalid handler for this request');

		$controllerKernel = new ControllerKernel(array());
	}

	/**
	 * Cek invalid handler [ADA TAPI TIDAK VALID]
	 */
	public function testCekInvalidHandlerParameterKey() {
		$request = Request::create('/home/index');
		$controllerKernel = new ControllerKernel(array(
			Route::HANDLER_CLASS => NULL,
			Route::HANDLER_ACTION => '',
		));

		$this->setExpectedException('Exception', 'Cannot find handler for this request');

		$controllerKernel->handle($request);
	}

	/**
	 * Cek valid handler dan invalid controller parameter
	 */
	public function testCekValidHandlerInvalidControllerParameter() {
		$request = Request::create('/home/index');
		$controllerKernel = new ControllerKernel(array(
			Route::HANDLER_CLASS => 'controlleryangtidakada',
			Route::HANDLER_ACTION => 'index',
		));

		$this->setExpectedException('Exception', 'Cannot find controller for this request');

		$controllerKernel->handle($request);
	}

	/**
	 * Cek valid handler dan invalid action parameter
	 */
	public function testCekValidHandlerInvalidAction() {
		$request = Request::create('/home/index');
		$controllerKernel = new ControllerKernel(array(
			Route::HANDLER_CLASS => 'home',
			Route::HANDLER_ACTION => 'actionyangtidakada',
		));

		$this->setExpectedException('Exception', 'Cannot find action for this request');

		$controllerKernel->handle($request);
	}

	/**
	 * Cek valid handler dan valid controller
	 */
	public function testCekValidHandlerValidControllerParameter() {
		$request = Request::create('/home/index');
		$controllerKernel = new ControllerKernel(array(
			Route::HANDLER_CLASS => 'home',
			Route::HANDLER_ACTION => 'index',
		));

		$response = $controllerKernel->handle($request);

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
	}
}