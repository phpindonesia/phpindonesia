<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app;

use Symfony\Component\HttpFoundation\Request;

/**
 * Application Controller Kernel
 *
 * @author PHP Indonesia Dev
 */
class ControllerKernel implements ControllerKernelInterface
{
	protected $controller, $action;

	/**
	 * Constructor.
	 *
	 * @param Array $handler A handler description
	 *
	 * @throws \Exception When an Exception occurs during processing
	 */
	public function __construct(array $handler) {
		// Validate the handler
		if ( !array_key_exists(Route::HANDLER_CLASS, $handler) ||  !array_key_exists(Route::HANDLER_CLASS, $handler)) {
			throw new \InvalidArgumentException('Invalid handler for this request');
		}

		$this->controller = $handler[Route::HANDLER_CLASS];
		$this->action = $handler[Route::HANDLER_ACTION];
	}

	/**
	 * Factory method
	 *
	 * @param Array $handler A handler description
	 */
	public static function make(array $handler) {
		return new static($handler);
	}

	/**
	 * Handles a Request to convert it to a Response.
	 *
	 * @param Request $request A Request instance
	 *
	 * @return Response A Response instance
	 *
	 * @throws \Exception When an Exception occurs during processing
	 */
	public function handle(Request $request) {
		$controllerClass = $this->controller;
		$action = $this->action;

		if (empty($controllerClass) or empty($action)) {
			throw new \InvalidArgumentException('Cannot find handler for this request');
		}

		try {
			$handlerClass = self::CONTROLLER_NAMESPACE.self::CONTROLLER.ucfirst($controllerClass);

			if ( ! class_exists($handlerClass)) {
				throw new \InvalidArgumentException('Cannot find controller for this request');
			}

			$handler = new $handlerClass($request);
			$callableHandler = array($handler, self::ACTION.ucfirst($action));

			if (is_callable($callableHandler)) {
				$response = call_user_func($callableHandler);
			} else {
				throw new \InvalidArgumentException('Cannot find action for this request');
			}

		} catch (\Exception $e) {
			throw $e;
		}

		return $response;
	}
}