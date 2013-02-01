<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

// @codeCoverageIgnoreStart
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$routes = new RouteCollection();

$routes->add('default', new Route('/', array('_controller' =>
    function (Request $request) {
        return new Response('You are in default route!');
    }
)));

$routes->add('universal_route_default', new Route('/{controller}', array('_controller' =>
    function (Request $request) {
        return new Response('You are in universal route controller!');
    }
), array('controller' => '[a-z]+')));

$routes->add('universal_route_action', new Route('/{controller}/{action}', array('_controller' =>
    function (Request $request) {
        return new Response('You are in universal route controller/action!');
    }
), array('controller' => '[a-z]+', 'action' => '[a-z]+')));

$routes->add('universal_route_action_id', new Route('/{controller}/{action}/{id}', array('_controller' =>
    function (Request $request) {
        return new Response('You are in universal route controller/action/id!');
    }
), array('controller' => '[a-z]+', 'action' => '[a-z]+', 'id' => '[0-9]+')));
// @codeCoverageIgnoreEnd