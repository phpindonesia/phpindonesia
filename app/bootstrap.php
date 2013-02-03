<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

// @codeCoverageIgnoreStart

/**
 * Global Constants
 */
defined('APPLICATION_PATH') OR define('APPLICATION_PATH', __DIR__);

require realpath(__DIR__.'/../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernel;

include 'routes.php';

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher));
$dispatcher->addSubscriber(new ExceptionListener(function (Request $request) {
    $msg = 'Something went wrong! ('.$request->get('exception')->getMessage().')';

    return new Response($msg, 500);
}));

$resolver = new ControllerResolver();

$kernel = new HttpKernel($dispatcher, $resolver);

$kernel->handle($request)->send();
// @codeCoverageIgnoreEnd
