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
 * Application ACL Interface
 *
 * @author PHP Indonesia Dev
 */
interface AclInterface
{
	const READ = 1;
	const WRITE = 2;
	const EDIT = 3;
	const DELETE = 4;
	const ANNOTATION = 'app\AclDriver';

	/**
	 * Constructor.
	 *
	 * @param Request $request Current request instance
	 * @param Reader  $reader Annotation reader instance
	 */
	public function __construct(Request $request, Reader $reader);

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
	public function isAllowed($permission = self::READ, $id = NULL, $action = '', $resource = NULL);

	/**
	 * Mengambil role user dalam request saat ini
	 *
	 * @return string User Role
	 */
	public function getCurrentRole();

	/**
	 * Mengambil nama resource dalam request saat ini
	 *
	 * @return string Controller Class
	 */
	public function getCurrentResource();

	/**
	 * isLogin
	 *
	 * Mengecek apakah user sedang login
	 */
	public function isLogin();
}