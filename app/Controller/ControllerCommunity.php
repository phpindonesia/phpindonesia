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
 * 	availableActions={"article","post"},
 *	config={
 *
 *		"article"={
 *			Acl::READ="all",
 *			Acl::WRITE="member,editor,admin",
 *			Acl::EDIT="owner,editor,admin",
 *			Acl::DELETE="admin"},
 *
 *		"post"={
 *			Acl::READ="all",
 *			Acl::WRITE="member,editor,admin",
 *			Acl::EDIT="owner,admin",
 *			Acl::DELETE="admin"}
 * })
 */
class ControllerCommunity extends ControllerBase
{
	/**
	 * Handler untuk ACL
	 *
	 * @param string Action
	 * @param mixed ID
	 */
	public function isOwner($type, $id) {
		$isOwner = false;
		$user = (int) $this->session->get('userId', '0');
		$id = (int) $id;

		switch ($type) {
			case 'article':
				$resource = ModelBase::factory('Node')->getArticle($id);
				$isOwner =  !empty($resource) && $resource->get('Uid') == $user;
				break;
		}

		return $isOwner;
	}

	/**
	 * Handler untuk GET/POST /users
	 */
	public function actionIndex() {
		// Cek ACL
		if ($this->acl->isAllowed(Acl::WRITE, null, 'article')) $this->data->set('allowWriteArticle', true);

		// Inisialisasi post/forum section
		$posts = ModelBase::factory('Node')->getAllPost(5);

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

		$users = ModelBase::factory('User')->getAllUser(3, $page, $filter);
		$pagination = ModelBase::buildPagination($users,'PhpidUsersQuery', $filter, $page, 3);

		// Template configuration
		$this->layout = 'modules/community/index.tpl';
		$data = ModelBase::factory('Template')->getComIndexData(compact('posts','articles','users','listTitle', 'listPage','pagination','searchQuery'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /post [a.k.a FORUM]
	 */
	public function actionPost() {
		// Inisialisasi
		$id = $this->request->get('id');
		$this->data->set('parseCode', true);

		// Cek ACL
		if ($this->acl->isAllowed(Acl::WRITE, $id)) $this->data->set('allowWriteArticle', true);
		if ($this->acl->isAllowed(Acl::EDIT, $id)) $this->data->set('allowEditor', true);

		if ($this->data->get('getData[new]','false',true) == 'true') {
			$this->data->set('allowEditor', true);
			$isList = true;
			$editorTitle = 'Buat Post';
			$editor = ModelBase::buildEditor('/provider/post','Masukkan judul tulisan','/community/post');
			$data = ModelBase::factory('Template')->getComPostData(compact('isList', 'editorTitle', 'editor'));
		} elseif (empty($id)) {
			// Inisialisasi post section
			$isList = true;
			$listTitle = 'Semua Post';
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
					array('column' => 'Title', 'value' => '%'.$query.'%', 'chainOrStatement' => TRUE),
				);
			}

			$searchQuery = $query;

			$posts = ModelBase::factory('Node')->getAllpost(7, $page, $filter);

			// Tambahkan filter post
			$filter[] = array('column' => 'Type', 'value' => 'post');
			$pagination = ModelBase::buildPagination($posts,'PhpidNodeQuery', $filter, $page, 10);

			$data = ModelBase::factory('Template')->getComPostData(compact('isList','posts','listTitle','listPage','pagination','searchQuery'));
		} else {
			// Detail post
			$isList = false;

			$post = ModelBase::factory('Node')->getPost($id,true);

			if ( ! $post->get('Nid')) {
				throw new \RuntimeException('Post tidak dapat ditemukan');
			}

			// Ambil comments data
			$comments = $post->get('comments') instanceof \PropelObjectCollection ? $post->get('comments')->toArray() : array();
			$title = strip_tags($post->get('previewText'));
			$posts = ModelBase::factory('Node')->getAllpost(5);

			$data = ModelBase::factory('Template')->getComPostData(compact('title','isList','posts', 'post','comments'));
		}

		// Template configuration
		$this->layout = 'modules/community/post.tpl';

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

		// Cek ACL
		if ($this->acl->isAllowed(Acl::WRITE, $id)) $this->data->set('allowWriteArticle', true);
		if ($this->acl->isAllowed(Acl::EDIT, $id)) $this->data->set('allowEditor', true);

		if ($this->data->get('getData[new]','false',true) == 'true') {
			$this->data->set('allowEditor', true);
			$isList = true;
			$editorTitle = 'Buat Tulisan';
			$editor = ModelBase::buildEditor('/provider/article','Masukkan judul tulisan','/community/article');
			$data = ModelBase::factory('Template')->getComArticleData(compact('isList', 'editorTitle', 'editor'));
		} elseif (empty($id)) {
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