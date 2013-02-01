<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * ControllerBase
 *
 * @author PHP Indonesia Dev
 */
class ControllerBase 
{
	protected $request;

	/**
	 * Constructor.
	 *
	 * @param Request $request A Request instance
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Redirect response.
	 *
	 * @param string $url URL Path
	 * @param int $status HTTP status code
	 * @param Array $headers HTTP Headers
	 *
	 * @return RedirectResponse A Response instance
	 */
	public function redirect($url = '', $status = 302, $headers = array())
	{
		return new RedirectResponse($url, $status, $headers);
	}

	/**
	 * Render response.
	 *
	 * @param string $content A Response content
	 * @param int $status HTTP status code
	 * @param Array $headers HTTP Headers
	 *
	 * @return Response A Response instance
	 */
	public function render($content = '', $status = 200, $headers = array())
	{
		return new Response($content, $status, $headers);
	}
}