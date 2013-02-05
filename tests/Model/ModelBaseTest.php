<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Model\ModelBase;

class ModelBaseTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek konsistensi model base instance
	 */
	public function testCekKonsistensiAppControllerBase()
	{
		$users = ModelBase::ormFactory('PhpidUsersQuery');

		$this->assertInstanceOf('\app\Model\Orm\om\BasePhpidUsersQuery', $users);
		$this->assertInstanceOf('ModelCriteria', $users);
		$this->setExpectedException('InvalidArgumentException', 'ORM class not found');

		ModelBase::ormFactory('UndefinedQuery');
	}
}