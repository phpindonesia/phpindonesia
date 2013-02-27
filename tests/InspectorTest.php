<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Inspector;

class InspectorTest extends PhpindonesiaTestCase {

	/**
	 * Cek konsistensi inspector instance
	 */
	public function testCekKonsistensiAppInspector() {
		$inspector = new Inspector();
		$this->assertInstanceOf('\app\InspectorInterface', $inspector);
		$this->assertObjectHasAttribute('substance', $inspector);
		$this->assertObjectHasAttribute('methods',$inspector);
		$this->assertObjectHasAttribute('properties',$inspector);
		$this->assertObjectHasAttribute('string',$inspector);
		$this->assertObjectHasAttribute('key',$inspector);
		$this->assertObjectHasAttribute('errors',$inspector);
		$this->assertObjectHasAttribute('errorDefaultMsg',$inspector);

		$this->setExpectedException('RuntimeException', 'Inspector doesnt have undefined');

		$undefined = $inspector->undefined;
	}

	/**
	 * Cek default message
	 */
	public function testCekDefaultMessageAppInspector() {
		$inspector = new Inspector();
		$inspector->setErrorDefaultMsg('This is default');

		$this->assertEquals('This is default', $inspector->compileErrors());
	}

	/**
	 * Cek custom validation
	 */
	public function testCekCustomValidatorAppInspector() {
		$inspector = new Inspector();
		$inspector->addValidator('cool', function($value) {
			return $value === 'cool';
		});

		$inspector->setSubstance(array('user' => 'not cool'));
		$inspector->ensure('user')->isCool('you are not cool');

		$this->setExpectedException('InvalidArgumentException', 'There are validation errors as follow'."\n".var_export($inspector->getErrors(), true));

		$inspector->validate();
	}

	/**
	 * Cek validation
	 */
	public function testCekValidationAppInspector() {
		$inspector = new Inspector();
		$this->assertTrue($inspector->validate());

		$inspector = new Inspector();
		$inspector->setSubstance(array('user' => 'not cool'));

		$this->setExpectedException('BadMethodCallException', 'Unknown validation method sillyRight');

		$inspector->ensure('user')->sillyRight('silly right?');
	}
}