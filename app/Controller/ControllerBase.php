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
	 * This is internally used by Travis-CI (JANGAN DIUTAK ATIK :)
	 *
	 * @codeCoverageIgnore
	 * @see app/routes.php
	 */
	public function actionSynchronize()
	{
		$userAgent = $this->request->server->get('HTTP_USER_AGENT');

		// TODO : More filtering
		if (strpos($userAgent, 'curl') !== FALSE) {
			passthru('cd ../phpindonesia;git checkout develop;git pull origin develop;git checkout master;git merge develop;git push origin master');
		}

		return $this->render('OK'."\n");
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
	 * @param mixed $content A Response content
	 * @param int $status HTTP status code
	 * @param Array $headers HTTP Headers
	 *
	 * @return Response A Response instance
	 */
	public function render($data = NULL, $status = 200, $headers = array())
	{
		// Persiapkan data yang akan di-render
		if (is_array($data) && array_key_exists('title', $data) && array_key_exists('content', $data)) {
			// Data yang dikirim memiliki parameter minimal yakni 'title' dan 'content'
			// Load twig dan template yang berkaitan
			$twigLoader = new \Twig_Loader_Filesystem(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Templates');
			$twig = new \Twig_Environment($twigLoader);
			$content = $twig->render('layout.html.tpl', $data);
		} else {
			// Data yang dikirim tidak memenuhi kriteria
			// Outputkan raw data
			$content = $data;
		}

		return new Response($content, $status, $headers);
	}
}
