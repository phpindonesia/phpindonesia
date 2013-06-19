<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Model\ModelTemplate;
use app\Model\Orm\PhpidComment;
use app\Parameter;

/**
 * ModelNode
 *
 * @author PHP Indonesia Dev
 */
class ModelComment extends ModelBase 
{
	protected $entity = 'PhpidComment';
	protected $query = 'PhpidCommentQuery';

	/**
	 * Create POST comment
	 *
	 * @param int Node ID
	 * @param array Comments data
	 */
	public function createPostComments($nid, $comments = array()) {
		$node = ModelBase::factory('Node')->getQuery()->findPK($nid);

		if ( ! empty($node)) {
			// Create all comments
			foreach ($comments as $comment) {
				$param = new Parameter($comment);
				$nid = $node->getNid();
				$name = $param->get('from[name]','-',true);
				$fid = $param->get('from[id]','-',true);
				$subject = $param->get('id');
				$thread = $param->get('message','-');
				$created = strtotime($param->get('created_time'));

				$this->createComment($name,$thread,$created,$subject,$nid,$fid);
			}
		}
	}

	/**
	 * Create general comment
	 *
	 * @param string Comentator name
	 * @param string Message 
	 * @param int Created time 
	 * @param string Subject/Signature
	 * @param int Node ID
	 * @param int User Facebook ID
	 * @param int User ID
	 */
	public function createComment($name = '-', $thread, $created = 0, $subject = '-', $nid = 0, $fid = 0, $uid = 0) {
		$comment = $this->getQuery()->findOneBySubject($subject);

		if (empty($comment)) {
			// build the comment data
			$comment = $this->getEntity();
			$comment->setLanguage('id');
			$comment->setStatus(1);
			$comment->setCreated($created == 0 ? time() : $created);
			$comment->setSubject($subject);
			$comment->setThread($thread);
			$comment->setName($name);
			$comment->setNid($nid);
			$comment->setFid($fid);
			$comment->setUid($uid);
			$comment->save();
		}

		return $comment;
	}
}