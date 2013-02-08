<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

/**
 * ModelTemplate
 *
 * @author PHP Indonesia Dev
 */
class ModelTemplate extends ModelBase 
{
	/**
	 * Provider untuk template Home
	 */
	public function getHomeData($otherData = array()) {
		$data = array(
			'title'	=> 'Home',
			'content' => 'Portal PHP Indonesia sedang dalam pembangunan.',
			'menu_top' => array(
				array('title' => 'Home', 'link' => '/'),
				array('title' => 'Masuk', 'link' => '/auth/login'),
				array('title' => 'Daftar', 'link' => '/auth/register'),
			),
			'menu_bottom' => array(),
		);

		if ( ! empty($otherData)) {
			$data = array_merge($data, $otherData);
		}

		return $data;
	}
}