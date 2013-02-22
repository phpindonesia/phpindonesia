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
				$userData = new Parameter($singleUser->toArray());
				$userData->set('Avatar', 'https://secure.gravatar.com/avatar/' . md5($userData->get('Mail')));

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
			$userData = new Parameter($user->toArray());
			$userCustomData = $userData->get('Data');

			// @codeCoverageIgnoreStart
			if ( ! empty($userCustomData)) {
				$userDataSerialized = fread($userCustomData,100000);
				$userData->set('AdditionalData', unserialize($userDataSerialized));
			}
			// @codeCoverageIgnoreEnd

			$userData->set('Avatar', 'https://secure.gravatar.com/avatar/' . md5($userData->get('Mail')));

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
}