<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application Route
 *
 * @author PHP Indonesia Dev
 */
class Route extends SymfonyRoute
{
	const HANDLER = '_handler';
	const HANDLER_CLASS = 'controllerClass';
	const HANDLER_ACTION = 'controllerAction';

	/**
	 * Constructor.
	 *
	 * Available options:
	 *
	 *  * compiler_class: A class name able to compile this route instance (RouteCompiler by default)
	 *
	 * @param string $pattern      The pattern to match
	 * @param array  $requirements An array of requirements for parameters (regexes)
	 * @param array  $options      An array of options
	 */
	public function __construct($pattern, array $requirements = array(), array $options = array()) {
		// Default handler class and action
		$handler = array(self::HANDLER_CLASS => 'home', self::HANDLER_ACTION => 'index');

		// Filter handler from requirements
		if (isset($requirements[self::HANDLER]) && is_array($requirements[self::HANDLER]))
		{
			// Any handler class found?
			if (array_key_exists(self::HANDLER_CLASS, $requirements[self::HANDLER]))
			{
				$handler[self::HANDLER_CLASS] = $requirements[self::HANDLER][self::HANDLER_CLASS];
			}

			// Any handler method/action found?
			if (array_key_exists(self::HANDLER_ACTION, $requirements[self::HANDLER]))
			{
				$handler[self::HANDLER_ACTION] = $requirements[self::HANDLER][self::HANDLER_ACTION];
			}

			// Unshift handler from requirements
			unset($requirements[self::HANDLER]);
		}

		$defaults = array('_controller' =>
			function (Request $request) use ($handler) {

				// Get controller from request instance
				if (($handlerClass = $request->get('controller')) && ! empty($handlerClass)) {
					$handler[Route::HANDLER_CLASS] = $handlerClass;
				}

				// Get action from request instance
				if (($handlerAction = $request->get('action')) && ! empty($handlerAction)) {
					$handler[Route::HANDLER_ACTION] = $handlerAction;
				}

				return ControllerKernel::make($handler)->handle($request);
			}
		);

		parent::__construct($pattern, $defaults, $requirements, $options);
	}
}