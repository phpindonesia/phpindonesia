<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use app\Parameter;
use app\Model\ModelBase;
use app\Facebook;

/**
 * ControllerCron
 *
 * @author PHP Indonesia Dev
 */
class ControllerCron extends ControllerBase
{
	const FB_GROUP_ID = '35688476100';

	/**
	 * Handler untuk GET/POST /cron/fb
	 * @codeCoverageIgnore
	 */
	public function actionFb() {
		// ID Group PHP Indonesia
		$group = self::FB_GROUP_ID;

		// Cari batch terakhir
		$lastBatch = ModelBase::factory('Batch')->getBatch();
		$access_token = $lastBatch->get('Token');
		$limit = 5;

		if (is_null($lastBatch->get('Batch'))) {
			$since = '2009-1-1';
			$until = '2009-2-2';
			$url   = '/'.$group.'/feed';
		} else {
			$url   = '/'.$group.$lastBatch->get('AdditionalData[next]',null,true);
		}

		$param = compact('access_token','limit','since','until');
		$facebook = new Facebook($this->request, array('appId' => '','secret' => ''));
		$rawResult = $facebook->api($url,$param);
		$result = is_array($rawResult) && !empty($rawResult) ? new Parameter($rawResult) : new Parameter();

		if ($result->get('paging') && $result->get('data')) {
			// TODO: Save the forum data via another CRON
			// Log the successful batch
			list($api_url,$next) = explode($group, $result->get('paging[next]','',true),2);
			list($api_url,$previous) = explode($group, $result->get('paging[previous]','',true),2);
			$data = $result->get('data');

			ModelBase::factory('Batch')->createBatch($access_token,compact('data','next','previous'));
		}

		return $this->renderJson(array('success' => count($result->get('data')) > 0));
	}
}
