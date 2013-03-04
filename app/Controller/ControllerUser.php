<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Model\ModelBase;

/**
 * ControllerUser
 *
 * @author PHP Indonesia Dev
 */
class ControllerUser extends ControllerBase
{
	/**
	 * Handler untuk GET/POST /users
	 */
	public function actionIndex() {
		// Inisialisasi
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

		$users = ModelBase::factory('User')->getAllUser(10, $page, $filter);
		$pagination = ModelBase::buildPagination($users,'PhpidUsersQuery', $filter, $page);

		// Template configuration
		$this->layout = 'modules/user/index.tpl';
		$data = ModelBase::factory('Template')->getUserData(compact('users','listTitle', 'listPage','pagination','searchQuery'));

		// Render
		return $this->render($data);
	}

	/**
	 * Handler untuk GET/POST /user/profile
	 */
	public function actionProfile() {
		$item = ModelBase::factory('User')->getUser($this->request->get('id'));

		$roleName = $this->acl->getCurrentRole($item->get('RoleValue'));
		$roleLabel = ($item->get('RoleValue',0) > 4) ? 'label-success' : ($item->get('RoleValue') > 2 ? 'label-info' : '');

		// Set additional attribute
		$item->set('roleName', strtoupper($roleName));
		$item->set('roleLabel', $roleLabel);

		// Check one or other mandatory fields
		// @codeCoverageIgnoreStart
		if ( empty($item) || ! $item->get('Mail') || ! $item->get('Name')) {
			throw new \LogicException('Maaf, ada yang salah dengan user_'.$this->request->get('id'));
		}
		// @codeCoverageIgnoreEnd

		// Inisialisasi article tab
		$articleTab = ModelBase::factory('User')->buildArticleTab($item, $this->data);

		// Inisialisasi activity tab
		$activityTab = NULL;

		// Finalisasi tabs
		$tabs = ModelBase::factory('User')->buildTabs($item->get('id'),$activityTab,$articleTab);

		// Template configuration
		$title = $item->get('Name');
		$this->layout = 'modules/user/profile.tpl';
		$data = ModelBase::factory('Template')->getUserData(compact('item', 'tabs', 'title'));

		// Render
		return $this->render($data);
	}
}
