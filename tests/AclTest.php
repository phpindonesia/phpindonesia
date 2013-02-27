<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Acl;
use app\AclDriver;
use app\Session;
use Doctrine\Common\Annotations\AnnotationReader as Reader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\HttpFoundation\Request;

class AclTest extends PhpindonesiaTestCase {

	protected $reader, $request;

	/**
	 * Setup
	 */
	public function before() {

		$session = new Session();
		$this->reader = new Reader();
		$this->request = Request::create('/community/article');
		$this->request->setSession($session);

		// Setting Doctrine Component
		AnnotationRegistry::registerAutoloadNamespace('app', realpath(APPLICATION_PATH.'/../'));
	}

	/**
	 * Cek konsistensi ACL instance
	 */
	public function testCekKonsistensiAppAcl() {
		$acl = new Acl($this->request,$this->reader);
		$this->assertInstanceOf('\app\AclInterface', $acl);
		$this->assertObjectHasAttribute('request', $acl);
		$this->assertObjectHasAttribute('session',$acl);
		$this->assertObjectHasAttribute('reader',$acl);
	}

	/**
	 * Cek Current ACL state
	 */
	public function testCekCurrentStateAttributesAppAcl() {
		$this->request->attributes->set('class','app\Controller\ControllerCommunity');
		$this->request->attributes->set('action','article');
		$acl = new Acl($this->request,$this->reader);
		$this->assertObjectHasAttribute('request', $acl);
		$this->assertObjectHasAttribute('session',$acl);
		$this->assertObjectHasAttribute('reader',$acl);

		$this->assertEquals('app\Controller\ControllerCommunity',$acl->getCurrentResource());
		$this->assertEquals('article',$acl->getCurrentAction());
	}

	/**
	 * Cek isAllowed
	 */
	public function testCekIsAllowedAppAcl() {
		$acl = new Acl($this->request,$this->reader);
		$this->assertObjectHasAttribute('request', $acl);
		$this->assertObjectHasAttribute('session',$acl);
		$this->assertObjectHasAttribute('reader',$acl);

		$this->assertTrue($acl->isAllowed(Acl::READ, NULL, 'article', 'app\Controller\ControllerCommunity'));
	}
}