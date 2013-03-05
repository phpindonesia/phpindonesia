<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Acl;
use app\AclDriver;
use app\Model\ModelBase;

/**
 * ControllerCommunity
 *
 * @author PHP Indonesia Dev
 * @AclDriver(
 *	name="Community",
 * 	availableActions={"article","forum"},
 *	config={
 *
 *		"article"={
 *			Acl::READ="all",
 *			Acl::WRITE="member,editor,admin",
 *			Acl::EDIT="editor,admin",
 *			Acl::DELETE="admin"},
 *
 *		"forum"={
 *			Acl::READ="all",
 *			Acl::WRITE="member,editor,admin",
 *			Acl::EDIT="admin",
 *			Acl::DELETE="admin"}
 * })
 */
class ControllerCommunity extends ControllerBase
{
	/**
	 * Handler untuk GET/POST /users
	 */
	public function actionIndex() {
		// Inisialisasi article section
		$articles = ModelBase::factory('Node')->getAllArticle(5);

		// Inisialisasi user section
		$listTitle = 'Semua Pengguna';
		$page = $this->data->get('getData[page]',1,true);
		$query = $this->data->get('getData[query]','',true);
		$filter = array();

		if ($_POST && isset($_POST['query'])) {
			$query = $_POST['query'];

			// Reset page
			$page = 1;
		}

		if ( ! empty($query)) {
			$listTitle = 'Pencarian "'.$query.'"';

			$filter = array(
				array('column' => 'Name', 'value' => $query.'%', 'chainOrStatement' => TRUE),
				array('column' => 'Mail', 'value' => $query.'%', 'chainOrStatement' => TRUE),
			);
		}

		$searchQuery = $query;

		$users = ModelBase::factory('User')->getAllUser(7, $page, $filter);
		$pagination = ModelBase::buildPagination($users,'PhpidUsersQuery', $filter, $page, 7);

		// Template configuration
		$this->layout = 'modules/community/index.tpl';
		$data = ModelBase::factory('Template')->getComIndexData(compact('articles','users','listTitle', 'listPage','pagination','searchQuery'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /article
	 */
	public function actionArticle() {
		// Inisialisasi
		$id = $this->request->get('id');
		$this->data->set('parseCode', true);

		if (empty($id)) {
			// Inisialisasi article section
			$isList = true;
			$listTitle = 'Semua Tulisan';
			$page = $this->data->get('getData[page]',1,true);
			$query = $this->data->get('getData[query]','',true);
			$filter = array();

			if ($_POST && isset($_POST['query'])) {
				$query = $_POST['query'];

				// Reset page
				$page = 1;
			}

			if ( ! empty($query)) {
				$listTitle = 'Pencarian "'.$query.'"';

				$filter = array(
					array('column' => 'Title', 'value' => $query.'%', 'chainOrStatement' => TRUE),
				);
			}

			$searchQuery = $query;

			$articles = ModelBase::factory('Node')->getAllArticle(7, $page, $filter);

			// Tambahkan filter artikel
			$filter[] = array('column' => 'Type', 'value' => 'article');
			$pagination = ModelBase::buildPagination($articles,'PhpidNodeQuery', $filter, $page, 7);

			$data = ModelBase::factory('Template')->getComArticleData(compact('isList','articles','listTitle','listPage','pagination','searchQuery'));
		} else {
			// Detail article
			$isList = false;

			$article = ModelBase::factory('Node')->getArticle($id);

			if ( ! $article->get('Nid')) {
				throw new \RuntimeException('Tulisan tidak dapat ditemukan');
			}

			$title = strip_tags($article->get('Title'));
			$item = ModelBase::factory('User')->getUser($article->get('Uid'));
			
			$roleName = $item->get('RoleValue');
			$roleLabel = ($roleName == 'admin') ? 'label-success' : ($roleName == 'editor' ? 'label-info' : '');

			// Set additional attribute
			$item->set('roleName', strtoupper($roleName));
			$item->set('roleLabel', $roleLabel);

			$data = ModelBase::factory('Template')->getComArticleData(compact('title','isList','item', 'article'));
		}

		// Template configuration
		$this->layout = 'modules/community/article.tpl';

		// Render
		return $this->render($data);
	}
}