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
use Symfony\Component\HttpKernel\Exception\FlattenException;
use app\Acl;
use app\Session;
use app\Parameter;
use app\Model\ModelBase;

/**
 * ControllerBase
 *
 * @author PHP Indonesia Dev
 */
class ControllerBase {

    protected $request;
    protected $session;
    protected $acl;
    protected $data;
    protected $layout = 'layout.tpl';

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
     * Constructor.
     *
     * @param Request $request A Request instance
     */
    public function __construct(Request $request) {
        $this->request = $request;

        // Initialize the session
        if ( ! $this->request->hasPreviousSession()) {
            $this->session = new Session();
            $this->session->start();
            $this->request->setSession($this->session);
        } else {
            $this->session = $this->request->getSession();
        }

        // Initialize Acl and Data instances
        $this->acl = new Acl($this->request);
        $this->data = new Parameter();

        // Before action hook
        if (is_callable(array($this,'beforeAction'))) {
            $this->beforeAction();
        }
    }

    /**
     * beforeAction hook (Global)
     */
    public function beforeAction() {
        // Assign acl object
        $this->data->set('acl', $this->acl);

        if ($this->acl->isLogin()) {
            // Assign user data
            $this->data->set('user', ModelBase::factory('Auth')->getUser($this->session->get('userId')));
        }

        // Assign POST data
        if ($_POST || $this->session->get('postData')) {
            $postData = array();

            if ($_POST) $postData = array_merge($postData, $_POST);

            if ($this->session->get('postData')) {
                $postData = array_merge($postData, $this->session->get('postData'));
                // Unset
                $this->session->set('postData', NULL);
            }

            $this->data->set('postData', $postData);
        }

        // Assign GET data
        if ($_GET) {
            $this->data->set('getData', $_GET);
        }

        if ($currentUrl = $this->request->server->get('PATH_INFO')) {
            $queryUrl = $_GET;
            
            // Exceptions for this keys
            $postToGetKeys = array('query');
            $flashedKeys = array('page');

            // add any detected keys that match exception elements
            foreach ($postToGetKeys as $postToGetKey) {
                if (isset($_POST[$postToGetKey])) $queryUrl[$postToGetKey] = $_POST[$postToGetKey];
            }

            // remove any flashed keys
            foreach($flashedKeys as $flashedKey) {
                if (isset($queryUrl[$flashedKey])) unset($queryUrl[$flashedKey]);
            }

            $currentQueryUrl = $currentUrl.'?';

            if ( ! empty($queryUrl)) {
                $currentQueryUrl .= http_build_query($queryUrl).'&';
            }

            // Set common URL variable
            $this->data->set('currentUrl', $currentUrl);
            $this->data->set('currentQueryUrl', $currentQueryUrl);
        }
    }

    /**
     * Exception handler
     *
     * @return Response
     */
    public function handleException() {
        $e = $this->request->get('exception');

        if (empty($e) || ! $e instanceof FlattenException) $e = new FlattenException();

        $this->layout = 'modules/error/general.tpl';
        $data = ModelBase::factory('Template')->getDefaultData();

        // Additional setter
        $this->data->set('title', 'Kesalahan');
        $this->data->set('content', $e);

        return $this->render($data);
    }

    /**
     * API untuk Session
     *
     * @return Session
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * API untuk ACL
     *
     * @return Acl
     */
    public function getAcl() {
        return $this->acl;
    }

    /**
     * API untuk Data
     *
     * @return Parameter
     */
    public function getData() {
        return $this->data;
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
            // sertakan semua global data object yang mungkin diperlukan
            $data = array_merge($data, $this->data->all());

            // yang diperlukan Twig. Load template yang berkaitan dan assign data.
            $twigLoader = new \Twig_Loader_Filesystem(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Templates');
            $twig = new \Twig_Environment($twigLoader);
            $content = $twig->render($this->layout, $data);
        } else {
            // Data yang dikirim tidak memenuhi kriteria
            // Outputkan raw data
            $content = $data;
        }

        return new Response($content, $status, $headers);
    }

}
