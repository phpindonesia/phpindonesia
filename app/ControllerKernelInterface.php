<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use Symfony\Component\HttpFoundation\Request;

/**
 * Application Controller Kernel Interface
 *
 * @author PHP Indonesia Dev
 */
interface ControllerKernelInterface
{
	 const CONTROLLER_NAMESPACE = '\app\Controller\\';
	 const CONTROLLER = 'Controller';
	 const ACTION = 'action';

	/**
	 * Factory method
	 *
	 * @param Array $handler A handler description
	 */
	public static function make(array $handler);

	/**
	 * Handles a Request to convert it to a Response.
	 *
	 * @param Request $request A Request instance
	 *
	 * @return Response A Response instance
	 *
	 * @throws \Exception When an Exception occurs during processing
	 */
	public function handle(Request $request);
}