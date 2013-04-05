<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Model\ModelTemplate;
use app\Model\Orm\PhpidBatch;
use app\Parameter;

/**
 * ModelBatch
 *
 * @author PHP Indonesia Dev
 */
class ModelBatch extends ModelBase 
{
	protected $entity = 'PhpidBatch';
	protected $query = 'PhpidBatchQuery';

	/**
	 * Fetch one Batch
	 *
	 * @param int Batch id
	 *
	 * @return Parameter
	 */
	public function getBatch($id = 0) {
		// Return last batch as default
		if (empty($id)){
			$batch = $this->getQuery()->orderByTimestamp('desc')->findOne();
		} else {
			// Get batch
			$batch = $this->getQuery()->findPK($id);
		}


		// Extract
		return empty($batch) ? new Parameter() : $this->extractBatch($batch);
	}

	/**
	 * Buat batch
	 *
	 * @param string $token
	 * @param array $batchData
	 * @return PhpidBatch 
	 */
	public function createBatch($token, $batchData = array()) {
		// Get last batch
		$lastBatch = $this->getQuery()->orderByBid('desc')->findOne();
		$bid = empty($lastBatch) ? 1 : ($lastBatch->getBid() + 1);

		$batch = $this->getEntity();
		$batch->setBid($bid);
		$batch->setToken($token);
		$batch->setBatch(serialize($batchData));
		$batch->setTimestamp(time());

		$batch->save();

		return $batch;
	}

	/**
	 * Extract batch
	 *
	 * @param PhpidBatch User object
	 * @return Parameter
	 */
	protected function extractBatch(PhpidBatch $batch) {
		$batchData = new Parameter($batch->toArray());
		$batchCustomData = $batchData->get('Batch');

		// @codeCoverageIgnoreStart
		if ( ! empty($batchCustomData)) {
			// Get data from opening stream
			$streamName = (string) $batchCustomData;

			if (ModelBase::$stream->has($streamName)) {
				$batchDataSerialized = ModelBase::$stream->get($streamName);
			} else {
				$batchDataSerialized = @stream_get_contents($batchCustomData);
				ModelBase::$stream->set($streamName, $batchDataSerialized);
			}

			// Now write back
			$additionalData = unserialize($batchDataSerialized);
			$batchData->set('AdditionalData', $additionalData);
		}
		// @codeCoverageIgnoreEnd

		return $batchData;
	}
}