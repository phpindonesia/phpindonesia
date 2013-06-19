<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */
defined('APPLICATION_DEBUG') OR define('APPLICATION_DEBUG', true);
defined('APPLICATION_PATH') OR define('APPLICATION_PATH', realpath(__DIR__.'/../app'));
defined('ASSET_PATH') OR define('ASSET_PATH', realpath(__DIR__.'/../public'));
defined('CONFIG_PATH') OR define('CONFIG_PATH', realpath(__DIR__.'/../conf'));
defined('CACHE_PATH') OR define('CACHE_PATH', realpath(__DIR__.'/../cache'));
require realpath(__DIR__.'/../vendor/autoload.php');
require __DIR__.'/PhpindonesiaTestCase.php';

/* Add maximum limit */
set_time_limit(300);