<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Annotations\AnnotationReader as Reader;
use app\AclDriver;

/**
 * Application ACL
 *
 * @author PHP Indonesia Dev
 */
class Acl
{
	const READ = 1;
	const WRITE = 2;
	const EDIT = 3;
	const DELETE = 4;
	const ANNOTATION = 'app\AclDriver';
	protected $request;
	protected $session;
	protected $reader;

	/**
	 * Constructor.
	 *
	 * @param Request $request Current request instance
	 */
	public function __construct(Request $request, Reader $reader) {
		// Get request and extract the session
		$this->request = $request;
		$this->session = $this->request->getSession();

		// Get reader engine
		$this->reader = $reader;
	}

	/**
	 * isAllowed
	 *
	 * API utama untuk ACL
	 *
	 * API ini akan membaca anotasi yang berkaitan dengan resource
	 * yang akan diakses, untuk kemudian melakukan pengecekan terhadap
	 * status/role user dan menentukan apa yang boleh dan tidak boleh
	 *
	 * @param string Resource action  : article,forum,gallery,etc
	 * @param string Permission Type : read,write,delete
	 * @param int Resource id
	 * @param string Resource Jika tidak dipass, maka akan diambil dari request
	 * @return bool 
	 */
	public function isAllowed($permission = self::READ, $id = NULL, $action = '', $resource = NULL) {
		$granted = false;

		if (empty($resource) || ! class_exists($resource)) {
			// Ambil dari request
			$resource = $this->getCurrentResource();
		}

		if (empty($action)) {
			// Ambil dari request
			$action = $this->request->get('action','undefined');
		}

		$resourceReflection = new \ReflectionClass($resource);
		$driver = $this->reader->getClassAnnotation($resourceReflection, self::ANNOTATION);

		if ( ! empty($driver) && $driver instanceof AclDriver && $driver->inRange($action)) {
			// Action ada dalam range
			$config = $driver->getConfig($action);

			// Lihat permission
			$granted = $driver->grantPermission($permission, $config, $this->getCurrentRole());
		}

		return $granted;
	}

	/**
	 * Mengambil role user dalam request saat ini
	 *
	 * @return string User Role
	 */
	public function getCurrentRole() {
		return 'member';
	}

	/**
	 * Mengambil nama resource dalam request saat ini
	 *
	 * @return string Controller Class
	 */
	public function getCurrentResource() {
		return $this->request->attributes->get('class');
	}

	/**
	 * isLogin
	 *
	 * Mengecek apakah user sedang login
	 */
	public function isLogin() {
		if (empty($this->session)) return false;

		return $this->session->get('login', false);
	}

	/**
	 * isContainFacebookData
	 *
	 * Mengecek apakah user sedang login dengan FB
	 */
	public function isContainFacebookData() {
		if (empty($this->session)) return false;

		return is_array($this->session->get('facebookData'));
	}
}