<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Parameter;

class ParameterTest extends PhpindonesiaTestCase {

	/**
	 * Cek konsistensi parameter instance
	 */
	public function testCekKonsistensiAppParameter()
	{
		$parameter = new Parameter(array('foo' => 'bar'));
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\ParameterBag', $parameter);
		$this->assertEquals('bar', $parameter->foo());
		$this->assertEmpty($parameter->undefined());
	}
}