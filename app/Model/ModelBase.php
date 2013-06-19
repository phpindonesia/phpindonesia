<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use \PropelCollection as Collection;
use app\Parameter;
use app\Inspector;
use \ModelCriteria;

/**
 * ModelBase
 *
 * @author PHP Indonesia Dev
 */
class ModelBase 
{
	const SET_UP = 'setUp';
	const PREFIX = 'Model';
	const ORM = 'Orm';
	protected $inspector;
	static $stream = false;
	static $req = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->inspector = new Inspector();

		// Add common validator method
		$this->inspector->addValidator('empty', function($value) {
		  return empty($value);
		});

		// Before hook
		if (is_callable(array($this, self::SET_UP))) {
			$this->setUp();
		}
	}

	/**
	 * Get active query
	 */
	public function getQuery() {
		return ModelBase::ormFactory($this->query);
	}

	/**
	 * Get active entity
	 */
	public function getEntity() {
		return ModelBase::ormFactory($this->entity);
	}

	/**
	 * API untuk Inspector
	 *
	 * @return Inspector
	 */
	public function getInspector() {
		return $this->inspector;
	}

	/**
	 * API untuk wrap object dengan propel collection
	 *
	 * @param mixed
	 * @return PropelCollection
	 */
	public function wrapCollection($propelObject = NULL) {
		if ($propelObject instanceof Collection) return $propelObject;

		$collectionData = is_array($propelObject) ? $propelObject : array($propelObject);

		return new Collection($collectionData);
	}

	/**
	 * Factory method to manufactoring app\Models easier
	 *
	 * @param string $class Model Class Suffix ('Template' will be translated to 'ModelTemplate')
	 * @param Parameter $parameter Parameter that needed to instantiate the model
	 *
	 * @throws InvalidArgumentException if Model class doesn't exists
	 */
	public static function factory($class, $parameter = NULL) {
		$class = __NAMESPACE__ . '\\' . self::PREFIX . $class;

		if ( ! class_exists($class)) {
			throw new \InvalidArgumentException('Model class not found');
		}

		return ($parameter instanceof Parameter) ? new $class($parameter) : new $class();
	}

	/**
	 * Factory method to manufactoring ORM models easier
	 *
	 * @param string $class ORM Class
	 *
	 * @throws InvalidArgumentException if ORM class doesn't exists
	 */
	public static function ormFactory($class) {
		// Initialize stream once
		if ( ! ModelBase::$stream) {
			ModelBase::$stream = new Parameter();
		}

		$ormClass = __NAMESPACE__ . '\\' . self::ORM . '\\' . $class;

		if ( ! class_exists($ormClass)) {
			throw new \InvalidArgumentException('ORM class not found');
		}

		return new $ormClass();
	}

	/**
	 * Filter method for Propel Query
	 *
	 * @param Parameter Filter array
	 * @param ModelCriteria the query instance
	 * @return ModelCriteria filtered query instance
	 */
	public static function filterQuery(ModelCriteria $query, Parameter $filter) {
	 	$column = $filter->get('column','Undefined');

	 	if (is_callable(array($query, 'filterBy'.ucfirst($column)), false, $filterBy)) {
	 		// Add OR statement while necessary
	 		if ($filter->get('chainOrStatement')) $query->_or();
	 		
	 		$query = call_user_func_array(array($query, $filterBy), array($filter->get('value','')));
		}

		return $query;
	 }

	 /**
	  * Build editor 
	  *
	  * @param string Action URL
	  * @param string Prompt
	  * @param string URL redirect after save
	  * @return string HTML
	  */
	 public static function buildEditor($action,$prompt = null, $redirect = '/home') {
	 	return new Parameter(array(
	 		'action' => $action,
	 		'prompt' => $prompt,
	 		'redirect' => $redirect,
	 	));
	 }

	 /**
	  * Build pagination information based some query object
	  *
	  * @param array the query instance
	  * @param string the related query class name
	  * @param array the related query filter
	  * @param int current page
	  * @param int total item per/page
	  * @return Parameter contain all pagination information
	  */
	 public static function buildPagination($objectCollection = array(), $ormClassName, $filter = array(), $currentPage = 1, $perPage = 10) {
	 	$result = new Parameter();

	 	// Get total entity
	 	$query = self::ormFactory($ormClassName);

	 	// @codeCoverageIgnoreStart
	 	if ( ! empty($filter)) {
	 		foreach ($filter as $where) {
				if ( ! $where instanceof Parameter) {
					$where = new Parameter($where);
				}

		 		$query = self::filterQuery($query, $where);
			}
	 	}

	 	$totalCount = $query->count();
	 	$currentCount = count($objectCollection);

	 	$totalPage = (int) ceil($totalCount/$perPage);
	 	$previousPage = ($currentPage == 1) ? $currentPage : $currentPage-1;
	 	$nextPage = ($currentPage == $totalPage) ? $currentPage : $currentPage+1;

	 	// Building Page collection
	 	$pages = array();
	 	$maxPage = ($totalPage > 11) ? 11 : $totalPage;
	 	$medianPage = (($totalPage/2) > 6) ? 6 : 0;

	 	for ($i=1; $i < ($maxPage+1); $i++) { 
	 		if ($i == $medianPage) {
		 		$page = new Parameter(array(
		 			'number' => '...',
		 			'class' => ' ',
		 		));
	 		} elseif ($i > $medianPage && $maxPage < $totalPage) {
	 			$iRevert = (($totalPage-$medianPage)+$i)-floor($maxPage/2);
	 			$page = new Parameter(array(
		 			'number' => $iRevert,
		 			'class' => ($iRevert == $currentPage) ? 'disabled' : ' ',
		 		));
	 		}else {
	 			$page = new Parameter(array(
		 			'number' => $i,
		 			'class' => ($i == $currentPage) ? 'disabled' : ' ',
		 		));
	 		}

	 		$pages[] = $page;
	 	}
	 	// @codeCoverageIgnoreEnd

	 	$result->set('data', $objectCollection);
	 	$result->set('pages', $pages);
	 	$result->set('currentPage', $currentPage);
	 	$result->set('previousPage', $previousPage);
	 	$result->set('nextPage', $nextPage);
	 	$result->set('totalPage', $totalPage);
	 	$result->set('currentCount', $currentCount);
	 	$result->set('totalCount', $totalCount);
	 	$result->set('totalText', ' '.$totalCount.' ');

	 	return $result;
	 }

	 /**
	 * POST an API JSON data
	 *
	 * @param string $url
	 * @param array $data
	 * @return Parameter
	 * @throws RuntimeException
	 */
	public function postJsonData($url, $jsonData) {
		 try {
			// Start output buffer
			ob_start();

			//open connection
			static::$req = curl_init();

			$opt[] = array(
				CURLOPT_URL => $url,
				CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_FOLLOWLOCATION => TRUE,
				CURLOPT_VERBOSE => TRUE,
				CURLOPT_POSTFIELDS => $jsonData,
				CURLOPT_POST => 1,
			);

			$this->setRequestOption($opt);

			//Execute post
			$result = $this->executeRequest();

			//close connection
			curl_close(static::$req);

			// Capture the buffer and assign into content holder
			$result->set('body',ob_get_clean());
		} catch (\Exception $e) {
			throw new \RuntimeException('cURL POST error');
		}

		return $result;
	}

	/**
	 * POST an API data
	 *
	 * @param string $url
	 * @param array $data
	 * @param array $opt
	 * @return Parameter
	 * @throws RuntimeException
	 */
	public function postData($url, $data, $opt = array()) {
		 try {
			// Start output buffer
			ob_start();

			//open connection
			static::$req = curl_init();

			$opt[] = array(
				CURLOPT_URL => $url,
				CURLOPT_POSTFIELDS => http_build_query($data),
			);

			$this->setRequestOption($opt);

			//Execute post
			$result = $this->executeRequest();

			//close connection
			curl_close(static::$req);

			// Capture the buffer and assign into content holder
			$result->set('body', ob_get_clean());
		} catch (\Exception $e) {
			throw new \RuntimeException('cURL POST error');
		}

		return $result;
	}

	/**
	 * GET an API data
	 *
	 * @param string $url
	 * @param array $data
	 * @param array $opt
	 * @return Parameter
	 * @throws Exception
	 */
	public function getData($url, $data, $opt = array()) {
		$url .= '?'.http_build_query($data);

		try {
			// Start output buffer
			ob_start();

			//open connection
			static::$req = curl_init();

			$opt[] = array(
				CURLOPT_URL => $url,
			);

			$this->setRequestOption($opt);

			//Execute post
			$result = $this->executeRequest();

			//close connection
			curl_close(static::$req);

			// Capture the buffer and assign into content holder
			$result->set('body',ob_get_clean());
		} catch (\Exception $e) {
			throw new \RuntimeException('cURL GET error');
		}

		return $result;
	}

	/**
	 * DELETE an API data
	 *
	 * @param string $url
	 * @param array $opt
	 * @return int HTTP Code
	 * @throws Exception
	 */
	public function removeData($url, $opt = array()) {
		try {
			// Start output buffer
			ob_start();

			//open connection
			static::$req = curl_init();

			$opt[] = array(
				CURLOPT_URL => $url,
				CURLOPT_CUSTOMREQUEST => "DELETE",
			);

			$this->setRequestOption($opt);

			//Execute post
			$result = $this->executeRequest();
			$httpCode = curl_getinfo(static::$req, CURLINFO_HTTP_CODE);

			//close connection
			curl_close(static::$req);

			$body = ob_get_clean();
		} catch (\Exception $e) {
			throw new \RuntimeException('cURL DELETE error');
		}

		return $httpCode;
	}

	/**
	 * Set cURL options
	 *
	 * @param array $opt
	 */
	protected function setRequestOption($opt = array()) {
		krsort($opt);
		foreach (array_values($opt) as $singleOption) {
			foreach ($singleOption as $option => $value) {
				curl_setopt(static::$req, $option, $value);
			}
		}
	}

	/**
	 * Execute cURL request
	 *
	 * @return Parameter
	 */
	protected function executeRequest() {
		// Set the global UA
		curl_setopt(static::$req,CURLOPT_USERAGENT, "Depending/GH-Service");

		$result  = curl_exec(static::$req);
		$err     = curl_errno(static::$req); 
		$errmsg  = curl_error(static::$req) ;
		$head    = curl_getinfo(static::$req);

		return new Parameter(compact('result', 'err', 'errmsg', 'head'));
	}

	 /**
	  * Overide method for gracefully fail bad method
	  *
	  * @codeCoverageIgnore
	  */
	 public function __call($method, $arguments) {
	 	if ( ! method_exists($this, $method) && $method !== self::SET_UP) {
	 		throw new \BadMethodCallException(get_class($this) . ' did not contain '.$method);
	 	}
	 }
}