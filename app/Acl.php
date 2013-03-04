<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use app\AclInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Annotations\AnnotationReader as Reader;
use app\AclDriver;

/**
 * Application ACL
 *
 * @author PHP Indonesia Dev
 */
class Acl implements AclInterface
{
	protected $request;
	protected $session;
	protected $reader;

	/**
	 * Constructor.
	 *
	 * @param Request $request Current request instance
	 * @param Reader  $reader Annotation reader instance
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

		// Validasi resource
		$resource = (empty($resource) || ! class_exists($resource)) ? $this->getCurrentResource() : $resource;

		// Validasi action
		$action = (empty($action)) ? $action = $this->getCurrentAction() : $action;

		// Dapatkan driver
		$resourceReflection = new \ReflectionClass($resource);
		$driver = $this->reader->getClassAnnotation($resourceReflection, self::ANNOTATION);

		if ( ! empty($driver) && $driver instanceof AclDriverInterface && $driver->inRange($action)) {
			// Action ada dalam range, ambil config
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
		switch($this->session->get('role')) {
			case 1:
			case 2:
				$role = 'member';
				break;

			case 3:
			case 4:
				$role = 'editor';
				break;

			case 5:
				$role = 'admin';
				break;

			default:
				$role = 'guest';

		}

		return $role;
	}

	/**
	 * Mengambil nama resource dalam request saat ini
	 *
	 * @return string Controller Class
	 */
	public function getCurrentResource() {
		return $this->request->get('class','Undefined');
	}

	/**
	 * Mengambil nama action dalam request saat ini
	 *
	 * @return string Controller Action
	 */
	public function getCurrentAction() {
		return $this->request->get('action','undefined');
	}

	/**
	 * isLogin
	 *
	 * Mengecek apakah user sedang login
	 */
	public function isLogin() {
		return (empty($this->session)) ? false : $this->session->get('login', false);
	}

	/**
	 * isContainFacebookData
	 *
	 * Mengecek apakah user sedang login dengan FB
	 */
	public function isContainFacebookData() {
		return (empty($this->session)) ? false : is_array($this->session->get('facebookData'));
	}
}