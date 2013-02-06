<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */
defined('ASSET_FACTORY_PATH') OR define('ASSET_FACTORY_PATH', dirname(__DIR__)."/assets");
defined('BASE_PATH') OR define('BASE_PATH', __DIR__);
defined('APPLICATION_DEBUG') OR define('APPLICATION_DEBUG', true);
require realpath(__DIR__.'/../vendor/autoload.php');