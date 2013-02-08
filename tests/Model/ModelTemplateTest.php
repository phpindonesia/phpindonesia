<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Model\ModelBase;

class ModelTemplateTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek konsistensi model template instance
	 */
	public function testCekKonsistensiModelTemplate()
	{
		$template = ModelBase::factory('Template');

		$this->assertInstanceOf('\app\Model\ModelBase', $template);
		$this->assertInstanceOf('\app\Model\ModelTemplate', $template);
		$this->assertObjectHasAttribute('defaultData', $template);
	}

	/**
	 * Cek get default data
	 */
	public function testCekGetDefaultDataTemplate()
	{
		$template = ModelBase::factory('Template');
		$defaultData = $template->getDefaultData();

		$this->assertArrayHasKey('title', $defaultData);
		$this->assertArrayHasKey('content', $defaultData);
		$this->assertArrayHasKey('menu_top', $defaultData);
		$this->assertArrayHasKey('menu_bottom', $defaultData);
	}
}