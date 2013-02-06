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
use Assetic\FilterManager;
use Assetic\Filter\LessphpFilter;
use Assetic\Factory\AssetFactory;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\Factory\LazyAssetManager;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Assetic\Extension\Twig\TwigResource;
use Assetic\AssetWriter;

/**
 * ControllerBase
 *
 * @author PHP Indonesia Dev
 */
class ControllerBase {

    protected $request;
    public $layout = 'layout.html.tpl';

    /**
     * Constructor.
     *
     * @param Request $request A Request instance
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * This is internally used by Travis-CI (JANGAN DIUTAK ATIK :)
     *
     * @codeCoverageIgnore
     * @see app/routes.php
     */
    public function actionSynchronize() {
        $userAgent = $this->request->server->get('HTTP_USER_AGENT');

        // TODO : More filtering
        if (strpos($userAgent, 'curl') !== FALSE) {
            passthru('cd ../phpindonesia;git checkout master;git fetch origin;git merge origin/develop;git push origin master');
        }

        return $this->render('OK' . "\n");
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
    public function redirect($url = '', $status = 302, $headers = array()) {
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
    public function render($data = NULL, $status = 200, $headers = array()) {
        // Persiapkan data yang akan di-render
        if (is_array($data) && array_key_exists('title', $data) && array_key_exists('content', $data)) {
            // Data yang dikirim memiliki parameter minimal yakni 'title' dan 'content'
            // yang diperlukan Twig. Load template yang berkaitan dan assign data.
            $twigLoader = new \Twig_Loader_Filesystem(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Templates');
            $twig = new \Twig_Environment($twigLoader);
            $this->invokeAssetic($twig);
            $content = $twig->render($this->layout, $data);
        } else {
            // Data yang dikirim tidak memenuhi kriteria
            // Outputkan raw data
            $content = $data;
        }

        return new Response($content, $status, $headers);
    }

    protected function invokeAssetic(\Twig_Environment $twig) {
        $fm = new FilterManager();
        $fm->set('less', new LessphpFilter());
        $factory = new AssetFactory(ASSET_FACTORY_PATH);
        $factory->setFilterManager($fm);
        $twig->addExtension(new AsseticExtension($factory));

        $am = new LazyAssetManager($factory);
        // enable loading assets from twig templates
        $am->setLoader('twig', new TwigFormulaLoader($twig));
        // loop through all your templates
        $resource = new TwigResource($twig->getLoader(), $this->layout);
        $am->addResource($resource, 'twig');
        if (APPLICATION_DEBUG) {
            $writer = new AssetWriter(BASE_PATH);
            $writer->writeManagerAssets($am);
        }
    }

}
