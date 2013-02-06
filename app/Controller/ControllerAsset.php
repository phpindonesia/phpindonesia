<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use Assetic\FilterManager;
use Assetic\Filter\LessphpFilter;
use Assetic\Factory\AssetFactory;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\AssetManager;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\AssetCollection;

/**
 * ControllerHome
 *
 * @author PHP Indonesia Dev
 */
class ControllerAsset extends ControllerBase {

    public function actionCss() {

        $id = $this->request->get('id');
        $return = $this->render("Page not found", 404);
        if ($id == "main.css") {
            $fm = new FilterManager();
            $fm->set('less', new LessphpFilter());
            $factory = new AssetFactory(ASSET_FACTORY_PATH);
            $factory->setFilterManager($fm);
            $am = new AssetManager();
            $factory->setAssetManager($am);
            $css = $factory->createAsset(array(
                'less/bootstrap.less', // load every scss files from "/path/to/asset/directory/css/src/"
                    ), array(
                'less', // filter through the filter manager's "scss" filter
                    )
            );
            $return = $this->render($css->dump(), 200, array('Content-type' => 'text/css'));
        }
        return $return;
    }

    public function actionJs() {

        $id = $this->request->get('id');
        $return = $this->render("Page not found", 404);
        if ($id == "app.js") {
            $collection = new AssetCollection();
            $bootstrap = array(
                'js/bootstrap-alert.js',
                'js/bootstrap-modal.js',
                'js/bootstrap-dropdown.js',
                'js/bootstrap-scrollspy.js',
                'js/bootstrap-tab.js',
                'js/bootstrap-tooltip.js',
                'js/bootstrap-popover.js',
                'js/bootstrap-button.js',
                'js/bootstrap-collapse.js',
                'js/bootstrap-carousel.js',
                'js/bootstrap-typeahead.js',
                'js/bootstrap-affix.js'
            );
            foreach ($bootstrap as $js) {
                $collection->add(new FileAsset(ASSET_FACTORY_PATH . "/$js"));
            }
            $content = $collection->dump();
            $return = $this->render($content, 200, array('Content-type' => 'application/javascript'));
        }
        return $return;
    }
    
    public function actionImg() {
        $id = $this->request->get('id');
        $filename = ASSET_FACTORY_PATH . "/img/$id";
        if (!is_file($filename)) {return $this->render("Page Not Found", 404);}
        $content = file_get_contents($filename); 
        $mime = mime_content_type($filename);
        return $this->render($content, 200, array('Content-Type' => $mime, 'Content-Length' => filesize($filename)));
    }

}