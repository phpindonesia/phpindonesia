<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use app\InspectorInterface;

/**
 * Application Inspector
 *
 * @author PHP Indonesia Dev
 */
class Inspector implements InspectorInterface
{
	protected $substance = array();
	protected $methods = array();
	protected $properties = array();
	protected $string = null;
	protected $key = null;
	protected $errors = null;
	protected $errorDefaultMsg = null;

	/**
	 * Constructor
	 *
	 * @param array $substance Payload data
	 * @param string $errorDefaultMsg Default validation error message
	 */
	public function __construct() {
		$this->addDefaultMethods();
	}

	/**
	 * Chain provider
	 * 
	 * @param string $key Method key
	 * @param string $errorDefaultMsg
	 *
	 * @return Inspector
	 */
	public function ensure($key, $errorDefaultMsg=null) {
		if(!empty($this->substance)) {
			$this->string = $this->substance[$key]; 
			$this->key = $key;
			$this->errorDefaultMsg = $errorDefaultMsg;
		}

		return $this;
	}

	/**
	 * Add new validator
	 * 
	 * @param string $method name
	 * @param Closure $callback Callable method for validate
	 * @return Inspector
	 */
	public function addValidator($method, \Closure $callback) {
		$this->methods[strtolower($method)] = $callback;

		return $this;
	}

	/**
	 * Add default validator
	 *
	 * @codeCoverageIgnore
	 */
	public function addDefaultMethods() {
		$this->methods['null'] = function($str) {
			return $str === null || $str === '';
		};
		$this->methods['max'] = function($str, $max) {
			$len = strlen($str);
			return $len <= $max;
		};
		$this->methods['min'] = function($str, $min) {
			$len = strlen($str);
			return $len >= $min;
		};
		$this->methods['int'] = function($str) {
			return (string)$str === ((string)(int)$str);
		};
		$this->methods['float'] = function($str) {
			return (string)$str === ((string)(float)$str);
		};
		$this->methods['email'] = function($str) {
			return filter_var($str, FILTER_VALIDATE_EMAIL) !== false;
		};
		$this->methods['url'] = function($str) {
			return filter_var($str, FILTER_VALIDATE_URL) !== false;
		};
		$this->methods['ip'] = function($str) {
			return filter_var($str, FILTER_VALIDATE_IP) !== false;
		};
		$this->methods['alnum'] = function($str) {
			return ctype_alnum($str);
		};
		$this->methods['alpha'] = function($str) {
			return ctype_alpha($str);
		};
		$this->methods['contains'] = function($str, $needle) {
			return strpos($str, $needle) !== false;
		};
		$this->methods['sameas'] = function($value, $substanceComparison, $inspector) {
			$isSame = false;
			if ($inspector instanceof \app\Inspector) {
				$isSame = $value === $inspector->getSubstance($substanceComparison);
			}

			return $isSame;
		};
		$this->methods['regex'] = function($str, $pattern) {
			return preg_match($pattern, $str);
		};
		$this->methods['chars'] = function($str, $chars) {
			return preg_match("/[$chars]+/i", $str);
		};
	}

	/**
	 * Build error message
	 *
	 * @param string $title Error title
	 * @param string $separator
	 * @param bool $onlyFirstError Hanya parsing error pertama
	 * @return string $errorMsg
	 */
	public function compileErrors($title = null, $separator = '**', $onlyFirstError = false) {
		$errorMsg = '';

		if ( ! empty($title)) {
			$errorMsg .= '<h3>'.$title.'</h3>';
		}

		if ($this->hasErrors()) {
			foreach ($this->getErrors() as $substanceKey => $error) {
				$errorMsg .= ($onlyFirstError) ? current($error) : implode($separator, $error);
				$errorMsg .= $separator;
			}
		} elseif ($this->errorDefaultMsg) {
			$errorMsg .= $this->errorDefaultMsg;
		}

		return $errorMsg;
	}

	/**
	 * Error default message
	 *
	 * @param string $errorDefaultMsg
	 * @return Inspector
	 */
	public function setErrorDefaultMsg($errorDefaultMsg) {
		$this->errorDefaultMsg = $errorDefaultMsg;

		return $this;
	}

	/**
	 * Check whether current inspector has error
	 */
	public function hasErrors() {
		return !empty($this->errors);
	}

	/**
	 * Errors getter
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Get substance value
	 *
	 * @param string
	 * @param string
	 * @return mixed
	 */
	public function getSubstance($name = '', $default = false) {
		return isset($this->substance[$name]) ? $this->substance[$name] : $default;
	}

	/**
	 * Set substance
	 *
	 * @param array
	 * @return Inspector
	 */
	public function setSubstance($substance = array()) {
		$this->substance = $substance;

		return $this;
	}

	/**
	 * Has properties
	 *
	 * @param string
	 * @return bool
	 */
	public function hasProperty($name = '') {
		return isset($this->properties[strtolower($name)]);
	}

	/**
	 * Get properties
	 *
	 * @param string
	 * @param mixed $default 
	 * @return mixed
	 */
	public function getProperty($name = '', $default=false) {
		return $this->hasProperty($name) ? $this->properties[strtolower($name)] : $default;
	}

	/**
	 * Set properties
	 *
	 * @param string
	 * @param mixed Can be anything
	 * @return Inspector
	 */
	public function setProperty($name = '', $property) {
		$this->properties[strtolower($name)] = $property;
	}

	/**
	 * Final inspection
	 *
	 * @return bool True if all validation passed
	 * @throws InvalidArgumentException
	 */
	public function validate() {
		if($this->hasErrors()) {
			throw new \InvalidArgumentException('There are validation errors as follow'."\n".var_export($this->errors, true));
		}else{
			return true;
		}
	}

	/**
	 * Custom property getter
	 *
	 * @param string
	 * @throws RuntimeErrorException
	 */
	public function __get($name) {
		if ($this->hasProperty($name)) {
			return $this->getProperty($name);
		} 

		throw new \RuntimeException('Inspector doesnt have '.$name);
	}

	/**
	 * Overloading method
	 *
	 * @return Inspector
	 * @throws BadMethodCallException
	 */
	public function __call($method, $args) {
		$reverse = false;
		$validatorName = $method;
		$methodType = substr($method, 0, 2);

		if ($methodType === self::IS) {       //is<$validator>()
			$validatorName = substr($method, 2);
		} elseif ($methodType === self::NO) { //not<$validator>()
			$validatorName = substr($method, 3);
			$reverse = true;
		}

		$validatorName = strtolower($validatorName);

		if (!$validatorName || !isset($this->methods[$validatorName])) {
			throw new \BadMethodCallException('Unknown validation method '.$method);
		}

		$validator = $this->methods[$validatorName];

		$validatorClosure = new \ReflectionFunction($validator);
		$parameters = $validatorClosure->getParameters();
		$numParameters = $validatorClosure->getNumberOfParameters();

		array_unshift($args, $this->string);

		$errorMsg = array_pop($args);

		if($numParameters > sizeof($args))
		{
			// See if we need to attach Inspector
			foreach ($parameters as $numParam => $parameter) {
				if ($parameter->name == 'inspector') {
					$args[$numParam] = $this;
					break;
				}
			}
		}

		$result = call_user_func_array(array($validatorClosure,'invoke'), $args);
		$result = (bool)($result ^ $reverse);

		// @codeCoverageIgnoreStart
		if($result == false) {

			if(!isset($this->errors[$this->key]) || !is_array($this->errors[$this->key])) {
				$this->errors[$this->key] = array();
			}

			if(!empty($this->errorDefaultMsg)) {
				if(!in_array($this->errorDefaultMsg, $this->errors[$this->key])) {
					$this->errors[$this->key][] = $this->errorDefaultMsg;
				}
			}

			if(!empty($errorMsg)) {
				$this->errors[$this->key][] = $errorMsg;
			} else {
				$this->errors[$this->key][] = 'does not validate.';
			}

		}
		// @codeCoverageIgnoreEnd

		return $this; 
	}

}