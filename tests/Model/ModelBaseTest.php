<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Model\ModelBase;

class ModelBaseTest extends PhpindonesiaTestCase {

	/**
	 * Cek konsistensi Model Factory
	 */
	public function testCekKonsistensiModelFactory() {
		$template = ModelBase::factory('Template');

		$this->assertInstanceOf('\app\Model\ModelBase', $template);
		$this->assertInstanceOf('\app\Model\ModelTemplate', $template);
		$this->setExpectedException('InvalidArgumentException', 'Model class not found');

		ModelBase::factory('Undefined');
	}

	/**
	 * Cek gracefully method
	 */
	public function testCekGracefulModelMethod() {
		$template = ModelBase::factory('Template');

		$this->setExpectedException('BadMethodCallException', get_class($template) . ' did not contain getUndefinedMethod');

		$template->getUndefinedMethod();
	}

	/**
	 * Cek konsistensi ORM Factory
	 */
	public function testCekKonsistensiOrmFactory() {
		$users = ModelBase::ormFactory('PhpidUsersQuery');

		$this->assertInstanceOf('\app\Model\Orm\om\BasePhpidUsersQuery', $users);
		$this->assertInstanceOf('ModelCriteria', $users);
		$this->setExpectedException('InvalidArgumentException', 'ORM class not found');

		ModelBase::ormFactory('UndefinedQuery');
	}
}