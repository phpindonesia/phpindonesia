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
	const NODE_EXTRACT = 'extract';
	protected $entity = 'PhpidNode';
	protected $query = 'PhpidNodeQuery';

	/**
	 * Fetch one Node
	 *
	 * @param int Node id
	 * @param string Node Type
	 *
	 * @return Parameter
	 */
	public function getNode($id, $type = 'article') {
		// Silly
		if (empty($id)) return false;

		// Get node
		$node = $this->getQuery()->findPK($id);
		$method = self::NODE_EXTRACT.ucfirst($type);

		// Extract
		return (empty($node) || (!empty($node) && $node->getType() !== $type)) ? new Parameter() : $this->$method($node);
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
}