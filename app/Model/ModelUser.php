<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Model\Orm\PhpidUsers;
use app\Parameter;

/**
 * ModelUser
 *
 * @author PHP Indonesia Dev
 */
class ModelUser extends ModelBase 
{
	/**
	 * Fetch user lists
	 *
	 * @param int Limit result 
	 * @param int Pagination
	 * @param array Filter
	 *
	 * @return array Array of user object wrapped in ParameterBag
	 */
	public function getAllUser($limit = 0, $page = 1, $filter = array()) {
		// Inisialisasi
		$users = array();

		// Create user query
		$query = ModelBase::ormFactory('PhpidUsersQuery');

		// Limit
		if ( ! empty($limit)) $query->limit($limit);

		// Offset
		if ( ! empty($page)) $query->offset(($page-1)*$limit);

		// Filter
		if ( ! empty($filter)) {
			foreach ($filter as $where) {
				if ( ! $where instanceof Parameter) {
					$where = new Parameter($where);
				}

				$query = ModelBase::filterQuery($query, $where);
			}
		}

		// Order
		$query->orderByName();

		if (($allUsers = $query->find()) && ! empty($allUsers)) {

			foreach ($allUsers as $singleUser) {
				// Convert to plain array and adding any necessary data
				$userData = $this->extractUser($singleUser->toArray());
				$users[] = $userData->all();
			}
		}

		return new Parameter($users);
	}

	/**
	 * Ambil data user 
	 *
	 * @param int $id User UID
	 * @return Parameter
	 */
	public function getUser($id = NULL) {
		// Silly
		if (empty($id)) return false;

		// Get user
		$user = ModelBase::ormFactory('PhpidUsersQuery')->findPK($id);

		if ($user) {
			// Get other misc data
			$userData = $this->extractUser($user->toArray());
			$user = $userData;
		}
		
		return $user;
	}

	/**
	 * Buat user
	 *
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 * @return PhpidUsers 
	 */
	public function createUser($username, $email, $password) {
		// Get last user
		$lastUser = ModelBase::ormFactory('PhpidUsersQuery')->orderByUid('desc')->findOne();
		$uid = empty($lastUser) ? 1 : ($lastUser->getUid() + 1);

		$user = ModelBase::ormFactory('PhpidUsers');
		$user->setUid($uid);
		$user->setName($username);
		$user->setMail($email);
		$user->setPass($password);
		$user->setData(serialize(array()));

		$user->save();

		return $user;
	}

	/**
	 * Update data user
	 *
	 * @param int $id User UID
	 * @param array $data regular data
	 *
	 * @return mixed
	 */
	public function updateUser($id = NULL, $data = array()) {
		// Silly
		if (empty($id)) return false;

		// Get user
		$user = ModelBase::ormFactory('PhpidUsersQuery')->findPK($id);

		if ($user) {
			foreach ($data as $key => $value) {
				$setMethod = 'set'.ucfirst($key);
				if (is_callable(array($user,$setMethod))) {
					$user = call_user_func_array(array($user,$setMethod), array($value));
				}
			}
		} else {
			return false;
		}

		$user->save();

		return $this->getUser($user->getUid());
	}

	/**
	 * Update custom data user
	 *
	 * @param int $id User UID
	 * @param array $data Custom data
	 *
	 * @return mixed
	 */
	public function updateUserData($id = NULL, $data = array()) {
		// Silly
		if (empty($id)) return false;

		// Get user
		$user = ModelBase::ormFactory('PhpidUsersQuery')->findPK($id);

		if ($user) {
			// Get custom data
			$userData = new Parameter($user->toArray());
			$customData = $userData->get('Data');

			// @codeCoverageIgnoreStart
			if (empty($customData)) {
				// Straight forward
				$user->setData(serialize($data));
			} else {
				$userDataSerialized = @fread($customData,10000);
				try {
					$currentUserData = unserialize($userDataSerialized);
					if ( ! $currentUserData) $currentUserData = array();
					$currentUserData = array_merge($currentUserData, $data);
				} catch (\Exception $e) {
					$currentUserData = $data;
				}

				// Update custom data
				$user->setData(serialize($currentUserData));
			}
			// @codeCoverageIgnoreEnd
			
			$user->save();

			return $this->getUser($user->getUid());
		} else {
			return false;
		}
	}

	/**
	 * Build article tab
	 *
	 * @param Parameter $user
	 * @param Parameter $data
	 * @return String 
	 */
	public function buildArticleTab(Parameter $user,Parameter $data) {
		$articleTab = NULL;

		$listTitle = 'Semua Tulisan '.$user->get('Name');
		$page = $data->get('getData[page]',1,true);
		$filter = array(
			array('column' => 'Uid', 'value' => $user->get('Uid')),
		);
		$articles = ModelBase::factory('Node')->getAllArticle(7, $page, $filter);

		if (count($articles->all()) > 0) {
			$withoutAvatar = true;
			$filter[] = array('column' => 'Type', 'value' => 'article');
			$pagination = ModelBase::buildPagination($articles,'PhpidNodeQuery', $filter, $page, 7);

			$templateData = compact('articles','listTitle','listPage','pagination','withoutAvatar');
			$templateData = array_merge($data->all(),$templateData);
			$articleTab = ModelBase::factory('Template')->render('blocks/list/article.tpl', $templateData);
		}
		

		return $articleTab;
	}

	/**
	 * Build tabs data
	 *
	 * @param id $uid
	 * @param string $articleTab
	 * @return Parameter 
	 */
	public function buildTabs($uid = NULL,$activityTab = NULL, $articleTab = NULL) {
		$tabs = array(
			// Aktifitas tab
			new Parameter(array(
				'id' => 'activity', 
				'link' => 'Aktifitas', 
				'liClass' => empty($articleTab) ? 'active' : ' ', 
				'tabClass' => empty($articleTab) ? 'active in' : ' ', 
				'data' => empty($activityTab) ? '' : $activityTab)),

			// Artikel tab
			new Parameter(array(
				'id' => 'article', 
				'link' => 'Artikel', 
				'liClass' => !empty($articleTab) ? 'active' : ' ', 
				'tabClass' => !empty($articleTab) ? 'active in' : ' ', 
				'data' => empty($articleTab) ? '' : $articleTab)),
		);

		return $tabs;
	}

	/**
	 * Extract user
	 *
	 * @param array
	 * @return Parameter
	 */
	protected function extractUser($userArrayData = array()) {
		$userData = new Parameter($userArrayData);
		$userCustomData = $userData->get('Data');

		// @codeCoverageIgnoreStart
		if ( ! empty($userCustomData)) {
			// Get data from opening stream
			$streamName = (string) $userCustomData;

			if (ModelBase::$stream->has($streamName)) {
				$userDataSerialized = ModelBase::$stream->get($streamName);
			} else {
				$userDataSerialized = @stream_get_contents($userCustomData);
				ModelBase::$stream->set($streamName, $userDataSerialized);
			}

			// Now write back
			$additionalData = unserialize($userDataSerialized);
			$userData->set('AdditionalData', $additionalData);
			$userData->set('Fullname', isset($additionalData['fullname']) ? $additionalData['fullname'] : '-');
		}
		// @codeCoverageIgnoreEnd

		$userData->set('Avatar', 'https://secure.gravatar.com/avatar/' . md5($userData->get('Mail')));
		$userData->set('Date', 'Terdaftar '.date('d M Y', $userData->get('Created')));
		$userData->set('LastLogin', 'Terakhir tampak '.date('d M', $userData->get('Login')));

		return $userData;
	}
}