<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

use app\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernel;

class RouteTest extends PHPUnit_Framework_TestCase {

	/**
	 * Cek konsistensi router instance
	 */
	public function testCekKonsistensiAppRoute()
	{
		$router = new Route('/');

		$this->assertInstanceOf('\Symfony\Component\Routing\Route', $router);
	}

	/**
	 * Cek handler default route
	 */
	public function testCekHandlerDefaultAppRoute()
	{
		$routes = new RouteCollection();

		$routes->add('testing', new Route('/{controller}/{action}', array(
			'controller' => 'home',
			'action' => 'index',
		)));

		$request = Request::create('/home/index');

		$context = new RequestContext();
		$context->fromRequest($request);

		$matcher = new UrlMatcher($routes, $context);
		$dispatcher = new EventDispatcher();
		$dispatcher->addSubscriber(new RouterListener($matcher));

		$resolver = new ControllerResolver();
		$kernel = new HttpKernel($dispatcher, $resolver);
		$response = $kernel->handle($request);
		
		$this->assertInstanceOf('\Symfony\Component\Routing\RouteCollection', $routes);
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
	}

	/**
	 * Cek handler custom route
	 */
	public function testCekHandlerCustomAppRoute()
	{
		$router = new Route('/sesuatu', array(Route::HANDLER => array(
			Route::HANDLER_CLASS => 'akumaukontrollerini',
			Route::HANDLER_ACTION => 'akumauactionini',
		)));
		
		$this->assertInstanceOf('\Symfony\Component\Routing\Route', $router);
	}
}