<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Parameter;
use \Twig_SimpleFilter;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

/**
 * ModelTemplate
 *
 * @author PHP Indonesia Dev
 */
class ModelTemplate extends ModelBase 
{
    protected $defaultData = array(
        'title' => 'Unknown Error',
        'content' => 'Ada yang salah. Harap hubungi administrator.',
        'menu_top' => array(
            array('title' => 'Home', 'link' => '/'),
            array('title' => 'Masuk', 'link' => '/auth/login'),
            array('title' => 'Daftar', 'link' => '/auth/register'),
        ),
        'menu_bottom' => array(),
    );

    /**
     * Render data ke template via Twig
     *
     * @param string $template eg:layout.tpl
     * @param array $data 
     *
     * @return string HTML representation
     */
    public static function render($template, $data = array()) {
        // Inisialisasi Twig. Load template yang berkaitan dan assign data.
        $loader = new Twig_Loader_Filesystem(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Templates');
        $templateEngine = new Twig_Environment($loader);

        // Filter declaration
        $filters = array(
            new Twig_SimpleFilter('toUserName', array(__CLASS__, 'getUserNameFromId')),
            new Twig_SimpleFilter('toUserFullName', array(__CLASS__, 'getUserFullnameFromId')),
            new Twig_SimpleFilter('toUserAvatar', array(__CLASS__, 'getUserAvatarFromId')),
            new Twig_SimpleFilter('displayArticleBody', array(__CLASS__, 'parseDocument')),
        );

        // Register filter
        foreach ($filters as $filter) $templateEngine->addFilter($filter);
        
        return $templateEngine->render($template, $data);
    }

    /**
     * Helper untuk parsing text 
     *
     * @param string $text
     * @param int $maxLength
     * @param bool $stripped
     * @return string $text Formatted text
     */
    public static function formatText($text = '', $maxLength = 0, $stripped = TRUE) {
        // Perlu escape?
        if ($stripped) {
            $text = strip_tags($text);
        }

        // Format
        if ($maxLength > 0) {
            if (strlen($text) > $maxLength) {
                $text = substr($text, 0, ($maxLength-3)).'...';
            }
        }
        
        return $text;
    }

    /**
     * Provider untuk template Home
     *
     * @param array $otherData Data dari model lain
     *
     * @return array $finalData
     * @see ModelTemplate::finalData
     */
    public function getHomeData($otherData = array()) {
        $data = array(
            'title' => 'Home',
            'content' => NULL,
        );

        return $this->prepareData($data, $otherData);
    }

     /**
     * Provider untuk template Setting
     *
     * @param array $otherData Data dari model lain
     *
     * @return array $finalData
     * @see ModelTemplate::finalData
     */
    public function getSettingData($otherData = array()) {
        $data = array(
            'title' => 'Setelan',
            'content' => NULL,
            'menus' => array(
                new Parameter(array(
                    'liClass' => 'nav-header',
                    'text' => 'Setelan',
                )),

                new Parameter(array('liClass' => 'divider')),

                new Parameter(array(
                    'liClass' => 'nav-header',
                    'text' => 'Profil',
                )),
                new Parameter(array(
                    'liClass' => '',
                    'text' => 'Informasi',
                    'link' => '/setting/info',
                    'icon' => 'icon-info-sign',
                )),

                new Parameter(array('liClass' => 'divider')),

                new Parameter(array(
                    'liClass' => 'nav-header',
                    'text' => 'Akun',
                )),
                new Parameter(array(
                    'liClass' => '',
                    'text' => 'Email',
                    'link' => '/setting/mail',
                    'icon' => 'icon-envelope',
                )),
                 new Parameter(array(
                    'liClass' => '',
                    'text' => 'Password',
                    'link' => '/setting/password',
                    'icon' => 'icon-key',
                )),
            ),
        );

        return $this->prepareData($data, $otherData);
    }

    /**
     * Provider untuk template User
     *
     * @param array $otherData Data dari model lain
     *
     * @return array $finalData
     * @see ModelTemplate::finalData
     */
    public function getUserData($otherData = array()) {
        $data = array(
            'title' => 'Pengguna',
            'content' => NULL,
        );

        return $this->prepareData($data, $otherData);
    }

     /**
     * Provider untuk template CommunityIndex
     *
     * @param array $otherData Data dari model lain
     *
     * @return array $finalData
     * @see ModelTemplate::finalData
     */
    public function getComIndexData($otherData = array()) {
        $data = array(
            'title' => 'Komunitas',
            'content' => NULL,
        );

        return $this->prepareData($data, $otherData);
    }

    /**
     * Provider untuk template CommunityArticle
     *
     * @param array $otherData Data dari model lain
     *
     * @return array $finalData
     * @see ModelTemplate::finalData
     */
    public function getComArticleData($otherData = array()) {
        $data = array(
            'title' => 'Tulisan',
            'content' => NULL,
        );

        return $this->prepareData($data, $otherData);
    }

    /**
     * Provider untuk template Auth
     *
     * @param array $otherData Data dari model lain
     *
     * @return array $finalData
     * @see ModelTemplate::finalData
     */
    public function getAuthData($otherData = array()) {
        $data = array();

        return $this->prepareData($data, $otherData);
    }

    /**
     * Mendapat default data
     *
     * @return array Default data
     */
    public function getDefaultData() {
        return $this->defaultData;
    }

     /**
     * Custom Twig filter untuk mendapat nama lengkap user
     *
     * @param int ID
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getUserFullnameFromId($id) {
        return ModelBase::factory('Template')->getUserNameFromId($id, 200);
    }

    /**
     * Custom Twig filter untuk mendapat nama user
     *
     * @param int ID
     * @param int Limit text
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getUserNameFromId($id, $limitLen = 10) {
        $userData = ModelBase::factory('User')->getUser($id);

        if (empty($userData)) {
            $name = 'Tak diketahui';
        } else {
            $name = ModelTemplate::formatText($userData->get('Name'), $limitLen);
        }

        return $name;
    }

    /**
     * Custom Twig filter untuk mendapat avatar
     *
     * @param int ID
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getUserAvatarFromId($id) {
        $userData = ModelBase::factory('User')->getUser($id);

        if (empty($userData)) {
            $avatar = 'https://secure.gravatar.com/avatar/'.md5('Tak diketahui');
        } else {
            $avatar = $userData->get('Avatar');
        }

        return $avatar;
    }

    /**
     * Custom Twig filter untuk mendisplay body value
     *
     * @param object Document object bundled with Parameter
     * @return string Parsed body
     * @codeCoverageIgnore
     */
    public function parseDocument(Parameter $param) {
        $type = $param->get('bodyFormat');

        // Validate type
        if ($type == 'markdown') {
            $type = strpos($param->get('body'), '<p') === false ? 'markdown' : 'full_html';
        }

        if ($type == 'full_html') {
            // Take care code tag
            $bodyText = str_replace(array('<code>','</code>'), array('<textarea class="codeParseable">','</textarea>'), $param->get('body'));
        } else {
            // @TODO : Markdown parser if necessary
            $bodyText = strip_tags($param->get('body'));
        }

        return $bodyText;
    }

    /**
     * PrepareData
     *
     * @param array $data Data default tiap section
     * @param array $otherData Data dari model lain
     *
     * @return array $finalData
     */
    protected function prepareData($data = array(), $otherData = array()) {
        $finalData = $this->defaultData;

        // Hanya merge jika terdapat data
        if ( ! empty ($data)) {
            $finalData = array_merge($finalData,$data);
        }

        if ( ! empty ($otherData)) {
            $finalData = array_merge($finalData, $otherData);
        }

        return $finalData;
    }
}