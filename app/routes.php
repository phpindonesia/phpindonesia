<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

// @codeCoverageIgnoreStart
use app\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

/**
 * Prototype(default) :
 *
 * GET /{controller}/{action}/{id}
 * GET /home 							=> ControllerHome::actionIndex()
 * GET /home/index 						=> ControllerHome::actionIndex()
 * GET /home/foo 						=> ControllerHome::actionFoo()
 * GET /home/foo/1 						=> ControllerHome::actionFoo()
 *
 * 
 * Custom :
 *
 * Misalnya, kita mau me-relasikan 
 * GET /sesuatu                         => ControllerFoo::actionBar()
 *
 * Maka kita cukup menambahkan route berikut kedalam RouteCollection :
 * $routes->add('nama_route', new Route('/sesuatu', array(Route::HANDLER => array(Route::HANDLER_CLASS => 'foo', Route::HANDLER_ACTION => 'bar'))));
 *
 * @see \app\Router.php for more details
 */

$routes->add('default', new Route('/', array(Route::HANDLER => array(Route::HANDLER_CLASS => 'home', Route::HANDLER_ACTION => 'index'))));

$routes->add('travis_sync', new Route('/synchronize', array(Route::HANDLER => array(Route::HANDLER_CLASS => 'base', Route::HANDLER_ACTION => 'synchronize'))));

$routes->add('universal_route_default', new Route('/{controller}', array('controller' => '[a-z]+')));

$routes->add('universal_route_action', new Route('/{controller}/{action}', array('controller' => '[a-z]+', 'action' => '[a-z]+')));

$routes->add('universal_route_action_id', new Route('/{controller}/{action}/{id}', array('controller' => '[a-z]+', 'action' => '[a-z]+', 'id' => '[0-9]+')));
// @codeCoverageIgnoreEnd