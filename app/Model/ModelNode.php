<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Model\ModelTemplate;
use app\Model\Orm\PhpidNode;
use app\Parameter;

/**
 * ModelNode
 *
 * @author PHP Indonesia Dev
 */
class ModelNode extends ModelBase 
{
	const GET = 'get';
	const NODE_EXTRACT = 'extract';
	protected $entity = 'PhpidNode';
	protected $query = 'PhpidNodeQuery';

	/**
	 * Fetch one Node
	 *
	 * @param int Node id
	 * @param string Node Type
	 * @param array Node Reference entities
	 *
	 * @return Parameter
	 */
	public function getNode($id, $type = 'article',$with = array()) {
		// Silly
		if (empty($id)) return false;

		// Get node
		$node = $this->getQuery()->findPK($id);

		$method = self::NODE_EXTRACT.ucfirst($type);

		// Extract
		$nodeData = (empty($node) || (!empty($node) && $node->getType() !== $type)) ? new Parameter() : $this->$method($node);

		// Check ref entities
		if ( ! empty($with)) {
			foreach ($with as $key => $value) {
				$entityDispatcher = self::GET . $value;
				$nodeData->set($key, $node->$entityDispatcher());
			}
		}

		return $nodeData;
	}

	/**
	 * Fetch Node article lists
	 *
	 * @param int Limit result 
	 * @param int Pagination
	 * @param array Filter
	 *
	 * @return array Array of Node object wrapped in ParameterBag
	 */
	public function getAllArticle($limit = 0, $page = 1, $filter = array()) {
		// Inisialisasi
		$articles = array();

		// Create article query
		$query = $this->getQuery();

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

		// Add filter Article
		$query->filterByType('article');

		// Order
		$query->orderByCreated('desc');

		if (($allArticles = $query->find()) && ! empty($allArticles)) {

			foreach ($allArticles as $singleArticle) {
				// Convert to plain array and adding any necessary data
				$articleData = $this->extractArticle($singleArticle);

				if ( ! empty($articleData)) {
					$articles[] = $articleData->all();
				}
			}
		}

		return new Parameter($articles);
	}

	/**
	 * Fetch Node Post lists
	 *
	 * @param int Limit result 
	 * @param int Pagination
	 * @param array Filter
	 *
	 * @return array Array of Node object wrapped in ParameterBag
	 */
	public function getAllPost($limit = 0, $page = 1, $filter = array()) {
		// Inisialisasi
		$posts = array();

		// Create Post query
		$query = $this->getQuery();

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

		// Add filter Post
		$query->filterByType('post');

		// Order
		$query->orderByCreated('desc');

		if (($allPosts = $query->find()) && ! empty($allPosts)) {

			foreach ($allPosts as $singlePost) {
				// Convert to plain array and adding any necessary data
				$postData = $this->extractPost($singlePost);

				if ( ! empty($postData)) {
					$posts[] = $postData->all();
				}
			}
		}

		return new Parameter($posts);
	}

	/**
	 * Fetch one Node article
	 *
	 * @param int Node id
	 *
	 * @return Parameter
	 */
	public function getArticle($id) {
		return $this->getNode($id,'article');
	}

	/**
	 * Fetch one Node post
	 *
	 * @param int Node id
	 * @param bool With comments
	 *
	 * @return Parameter
	 */
	public function getPost($id, $withComment = false) {
		return $this->getNode($id,'post',$withComment ? array('comments' => 'PhpidComments') : array());
	}

	/**
	 * Create an article (Node+FieldBody)
	 *
	 * @param int $uid Author id
	 * @param string $title Article title
	 * @param string $bodyData Article body (in Markdown)
	 * @return PhpidNode Node object with its body
	 */
	public function createArticle($uid, $title, $bodyData) {
		// build the body data
		$body = ModelBase::ormFactory('PhpidFieldDataBody');
		$body->setEntityType('node');
		$body->setBundle('article');
		$body->setLanguage('id');
		$body->setBodyFormat('markdown');
		$body->setBodyValue($bodyData);

		$node = $this->getEntity();
		$node->setType('article');
		$node->setLanguage('id');
		$node->setCreated(time());
		$node->setTitle($title);
		$node->setUid($uid);
		$node->setPhpidFieldDataBodys($this->wrapCollection($body));
		$node->save();

		return $node;
	}

	/**
	 * Create a POST (Node)
	 *
	 * @param string $name Post author's name
	 * @param string $message Post message
	 * @param int $created Post timestamp
	 * @param string $signature Post original ID if any
	 * @param int $fid Author Facebook id
	 * @param int $uid Author id
	 * @return PhpidNode Node object with its body
	 */
	public function createPost($name = '-', $message, $created = 0, $signature = '-', $fid, $uid = 0) {
		$node = $this->getQuery()->findOneBySignature($signature);

		if (empty($node)) {
			$body = ModelBase::ormFactory('PhpidFieldDataBody');
			$body->setEntityType('node');
			$body->setBundle('post');
			$body->setLanguage('id');
			$body->setBodyFormat('markdown');
			$body->setBodyValue($message);

			// build the post data
			$node = $this->getEntity();
			$node->setType('post');
			$node->setLanguage('id');
			$node->setCreated($created == 0 ? time() : $created);
			$node->setSignature($signature);
			$node->setTitle($message);
			$node->setName($name);
			$node->setFid($fid);
			$node->setUid($uid);
			$node->setPhpidFieldDataBodys($this->wrapCollection($body));
			$node->save();
		}

		return $node;
	}

	/**
	 * Extract artikel
	 *
	 * @param PhpidNode
	 * @return Parameter
	 */
	protected function extractArticle(PhpidNode $article) {
		$currentBodyData = current($article->getPhpidFieldDataBodys());
		$articleBodyData = new Parameter($currentBodyData->toArray());
		$articleData = new Parameter($article->toArray());

		if ($articleData->get('Nid')) {
			// Get author and set appropriate pub date
			$maxTitle = 20;
			$maxText = 60;
			$maxMediumText = 200;
			$articleData->set('Link', '/community/article/'.$articleData->get('Nid'));
			$articleData->set('AuthorLink', '/user/profile/'.$articleData->get('Uid'));
			$articleData->set('pubDate', date('d M, Y',$articleData->get('Created')));
			$articleData->set('previewTitle', ModelTemplate::formatText($articleData->get('Title'), $maxTitle));
			
			$articleData->set('body',$articleBodyData->get('BodyValue'));
			$articleData->set('bodyFormat',$articleBodyData->get('BodyFormat'));
			$articleData->set('previewText', ModelTemplate::formatText($articleBodyData->get('BodyValue'), $maxText, true));
			$articleData->set('previewMediumText', ModelTemplate::formatText($articleBodyData->get('BodyValue'), $maxMediumText,true));
		}
		
		return $articleData;
	}

	/**
	 * Extract post
	 *
	 * @param PhpidNode
	 * @return Parameter
	 */
	protected function extractPost(PhpidNode $post) {
		$currentBodyData = current($post->getPhpidFieldDataBodys());
		$postBodyData = new Parameter($currentBodyData->toArray());
		$postData = new Parameter($post->toArray());

		if ($postData->get('Nid')) {
			// Get author and set appropriate pub date
			$maxText = 60;
			$maxMediumText = 100;
			$postData->set('Link', '/community/post/'.$postData->get('Nid'));
			$postData->set('AuthorLink', $postData->get('Uid') ? '/user/profile/'.$postData->get('Uid') : '#!');
			$postData->set('pubDate', date('d M, Y',$postData->get('Created')));
			
			$postData->set('body',$postBodyData->get('BodyValue'));
			$postData->set('previewText', ModelTemplate::formatText($postData->get('Title'), $maxText, true));
			$postData->set('previewMediumText', ModelTemplate::formatText($postData->get('Title'), $maxMediumText,true));
		}
		
		return $postData;
	}
}