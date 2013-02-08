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
	protected $defaultData = array(
		'title'	=> 'Unknown Error',
		'content' => 'Ada yang salah. Harap hubungi administrator.',
		'menu_top' => array(
			array('title' => 'Home', 'link' => '/'),
			array('title' => 'Masuk', 'link' => '/auth/login'),
			array('title' => 'Daftar', 'link' => '/auth/register'),
		),
		'menu_bottom' => array(),
	);

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
			'title'	=> 'Home',
			'content' => 'Portal PHP Indonesia sedang dalam pembangunan.',
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