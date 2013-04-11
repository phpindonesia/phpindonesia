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
		$url   = '/'.$group.'/feed';
		$limit = 5;

		if (is_null($lastBatch->get('Batch'))) {
			$since = '2009-1-1';
			$until = '2009-2-2';
		} else {
			$urlComponents = $lastBatch->get('AdditionalData[next]',null,true);
			$urlComponents = parse_url($urlComponents);

			if (array_key_exists('query', $urlComponents)) {
				parse_str($urlComponents['query']);

				if (isset($until)) {
					unset($since);
				}
			}
		}

		$param = compact('access_token','limit','since','until','__paging_token');
		$facebook = new Facebook($this->request, array('appId' => '','secret' => ''));
		$rawResult = $facebook->api($url,$param);
		$result = is_array($rawResult) && !empty($rawResult) ? new Parameter($rawResult) : new Parameter();

		if (count($result->get('data')) == 0) {
			$param['until'] = time();
			unset($param['__paging_token']);

			$facebook = new Facebook($this->request, array('appId' => '','secret' => ''));
			$rawResult = $facebook->api($url,$param);
			$result = is_array($rawResult) && !empty($rawResult) ? new Parameter($rawResult) : new Parameter();
		}

		if ($result->get('paging') && $result->get('data')) {
			// TODO: Save the forum data via another CRON
			// Log the successful batch
			list($api_url,$next) = explode($group, $result->get('paging[next]','',true),2);
			list($api_url,$previous) = explode($group, $result->get('paging[previous]','',true),2);
			$data = $result->get('data');

			ModelBase::factory('Batch')->createBatch($access_token,compact('data','next','previous'));
		}

		return $this->renderJson(array('success' => count($result->get('data')) > 0,'data' => $rawResult));
	}

	/**
	 * Handler untuk GET/POST /cron/consumefb
	 * @codeCoverageIgnore
	 */
	public function actionConsumefb() {
		// Cari batch pertama sebelum 0
		$success = false;
		$lastBatch = ModelBase::factory('Batch')->getBatchAfterNil();
		$id = $lastBatch->get('Bid',null);
		$data = $lastBatch->get('AdditionalData[data]',null,true);

		if (!empty($id) && !empty($data) && is_array($data)) {
			foreach ($data as $post) {
				// Proses batch menjadi Forum POST
				$param = new Parameter($post);
				$signature = $param->get('id');
				$name = $param->get('from[name]','-',true);
				$fid = $param->get('from[id]','-',true);
				$message = htmlentities($param->get('message'));
				$created = strtotime($param->get('created_time'));

				$node = ModelBase::factory('Node')->createPost($name, $message, $created, $signature, $fid);

				// Ambil komentar jika ada
				if ($param->get('comments[count]',0,true) > 0) {
					$comments = $param->get('comments[data]',array(),true);
					if (! empty($comments)) {
						ModelBase::factory('Comment')->createPostComments($node->getNid(),$comments);
					}
				}

				// TODO: Ambil Like jika ada
			}
			
			// Hapus batch
			ModelBase::factory('Batch')->getQuery()->findPK($id)->delete();
			$success = true;
		}

		return $this->renderJson(array('success' => $success,'bid'=>$id));
	}

	/**
	 * Handler untuk GET/POST /cron/migratefid
	 * @codeCoverageIgnore
	 */
	public function actionMigratefid() {
		$processed = 0;
		$users = ModelBase::factory('User')->getQuery()->find();

		foreach ($users as $user) {
			$userCustomData = $user->getData();

			$streamName = (string) $userCustomData;

			if (ModelBase::$stream->has($streamName)) {
				$userDataSerialized = ModelBase::$stream->get($streamName);
			} else {
				$userDataSerialized = @stream_get_contents($userCustomData);
				ModelBase::$stream->set($streamName, $userDataSerialized);
			}

			if ($userDataSerialized) {
				$userData = new Parameter(unserialize($userDataSerialized));

				if ($userData->get('fb_uid')) {
					$processed++;
					$user->setFid($userData->get('fb_uid'));
					$user->save();
				}
			}
		}

		return $this->renderJson(array('success' => count($processed) > 0,'processed'=>$processed));
	}
}
