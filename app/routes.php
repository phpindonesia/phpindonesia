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

$routes->add('default', new Route('/'));

$routes->add('universal_route_default', new Route('/{controller}', array('controller' => '[a-z]+')));

$routes->add('universal_route_action', new Route('/{controller}/{action}', array('controller' => '[a-z]+', 'action' => '[a-z]+')));

$routes->add('universal_route_action_id', new Route('/{controller}/{action}/{id}', array('controller' => '[a-z]+', 'action' => '[a-z]+', 'id' => '[0-9]+')));
// @codeCoverageIgnoreEnd