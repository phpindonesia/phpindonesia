<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

/**
 * Application Inspector Interface
 *
 * @author PHP Indonesia Dev
 */
interface InspectorInterface 
{
	const IS = 'is';
	const NO = 'no';

	/**
	 * Chain provider
	 * 
	 * @param string $key Method key
	 * @param string $errorDefaultMsg
	 *
	 * @return Inspector
	 */
	public function ensure($key, $errorDefaultMsg=null);

	/**
	 * Add new validator
	 * 
	 * @param string $method name
	 * @param Closure $callback Callable method for validate
	 * @return Inspector
	 */
	public function addValidator($method, \Closure $callback);

	/**
	 * Add default validator
	 *
	 * @codeCoverageIgnore
	 */
	public function addDefaultMethods();

	/**
	 * Build error message
	 *
	 * @param string $title Error title
	 * @param string $separator
	 * @param bool $onlyFirstError Hanya parsing error pertama
	 * @return string $errorMsg
	 */
	public function compileErrors($title = null, $separator = '**', $onlyFirstError = false);

	/**
	 * Error default message
	 *
	 * @param string $errorDefaultMsg
	 * @return Inspector
	 */
	public function setErrorDefaultMsg($errorDefaultMsg);

	/**
	 * Check whether current inspector has error
	 */
	public function hasErrors();

	/**
	 * Errors getter
	 */
	public function getErrors(); 

	/**
	 * Get substance value
	 *
	 * @param string
	 * @param string
	 * @return mixed
	 */
	public function getSubstance($name = '', $default = false);

	/**
	 * Set substance
	 *
	 * @param array
	 * @return Inspector
	 */
	public function setSubstance($substance = array());

	/**
	 * Has properties
	 *
	 * @param string
	 * @return bool
	 */
	public function hasProperty($name = '');

	/**
	 * Get properties
	 *
	 * @param string
	 * @param mixed $default 
	 * @return mixed
	 */
	public function getProperty($name = '', $default=false);

	/**
	 * Set properties
	 *
	 * @param string
	 * @param mixed Can be anything
	 * @return Inspector
	 */
	public function setProperty($name = '', $property);

	/**
	 * Final inspection
	 *
	 * @return bool True if all validation passed
	 * @throws InvalidArgumentException
	 */
	public function validate();
}