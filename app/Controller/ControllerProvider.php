<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Model\ModelBase;
use app\Acl;

/**
 * ControllerProvider
 *
 * @author PHP Indonesia Dev
 */
class ControllerProvider extends ControllerBase
{
	/**
	 * Handler untuk GET/POST /provider/article
	 */
	public function actionArticle() {
		// Default
		$success = false;
		$data = null;

		// INPUT
		$id = $this->data->get('postData[id]','0',true);
		$title = $this->data->get('postData[title]','0',true);
		$content = $this->data->get('postData[content]','',true);

		// Only unit-test or authorized user could access the editor
		if (defined('STDIN') || $this->acl->isAllowed(Acl::WRITE, $id, 'article', 'app\\Controller\\ControllerCommunity')) {
			if ( ! empty($id)) {
				$article = ModelBase::factory('Node')->getQuery()->findPK($id);

				if (count($article) == 1) {
					if ( ! empty($content)) {
						// Update the content
						$dataBody = current($article->getPhpidFieldDataBodys());
						$dataBody->setBodyValue($content);
						$dataBody->save();
					}

					if ( ! empty($title)) {
						$article->setTitle($title);
						$article->save();
					}

					$success = true;
					$data = 'Konten berhasil diupdate';
				}
			} else {
				$title = $this->data->get('postData[input]',$title,true);
				$article = ModelBase::factory('Node')->createArticle($this->session->get('userId'), $title, $content);

				$success = !empty($article);
				$data = $success ? 'Konten berhasil disimpan' : '';
			}
		}
		
		return $this->renderJson(compact('success','data'));
	}
}
