<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Config;
use Symfony\Component\Yaml\Yaml;

class ConfigTest extends PHPUnit_Framework_TestCase
{
	/**
	*  Persiapan file
	*/
	public function setUp()
	{
		$this->twigConfigFile = realpath(__DIR__.'/../conf/twig.yml');
		$this->siteConfigFile = realpath(__DIR__.'/../conf/site.yml');
	}

	/**
	*  Cek keberadaan file dan cek permission
	*/
	public function testFirstCheck()
	{
		$this->assertTrue(is_readable($this->twigConfigFile));
		$this->assertTrue(is_readable($this->siteConfigFile));
	}

	/**
	*  Cek konfigurasi Twig
	*/
	public function testTwigConfig()
	{
		$twigConfig = Yaml::parse($this->twigConfigFile);

		$twigConfigTest['twig']['debug'] = true;

		$this->assertEquals($twigConfig, $twigConfigTest);
	}

	/**
	*  Cek konfigurasi Site
	*/
	public function testSiteConfig()
	{
		$siteConfig = Yaml::parse($this->siteConfigFile);

		$regex = '/^[A-Za-z0-9_\- ]+$/';

		$this->assertRegExp($regex, $siteConfig['site']['title']);
	}

	/**
	*  Cek fungsi Config::get(file.key)
	*/
	public function testConfigGet()
	{
		$test1 = Config::get('site.title');
		$this->assertEquals($test1, 'PHP Indonesia');

		$test2 = Config::get('twig.debug');
		$this->assertTrue($test2);
	}
}
