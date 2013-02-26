<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Model\Orm\PhpidNode;
use app\Parameter;

/**
 * ModelNode
 *
 * @author PHP Indonesia Dev
 */
class ModelNode extends ModelBase 
{
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
		$query = ModelBase::ormFactory('PhpidNodeQuery');

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
				$articleData = $this->extractArticle($singleArticle->toArray());

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
		// Silly
		if (empty($id)) return false;

		// Get user
		$article = ModelBase::ormFactory('PhpidNodeQuery')->findPK($id);

		// Extract
		return (empty($article)) ? new Parameter() : $this->extractArticle($article->toArray());
	}

	/**
	 * Extract artikel
	 *
	 * @param array
	 * @return Parameter
	 */
	protected function extractArticle($articleArrayData = array()) {
		$articleData = new Parameter($articleArrayData);

		if ($articleData->get('Nid')) {
			// Get related content
			$articleBodyData = new Parameter(ModelBase::ormFactory('PhpidFieldDataBodyQuery')->findOneByEntityTypeAndEntityId('node',$articleData->get('Nid'))->toArray());

			// Get author and set appropriate pub date
			$maxTitle = strlen($articleData->get('Title')) > 20 ? 20 : NULL;
			$maxText = strlen($articleBodyData->get('BodyValue')) > 60 ? 60 : NULL;
			$maxMediumText = strlen($articleBodyData->get('BodyValue')) > 200 ? 200 : NULL;
			$articleData->set('Link', '/community/article/'.$articleData->get('Nid'));
			$articleData->set('AuthorLink', '/user/profile/'.$articleData->get('Uid'));
			$articleData->set('pubDate', date('d M, Y',$articleData->get('Created')));
			$articleData->set('previewTitle', substr($articleData->get('Title'), 0, $maxTitle).( ! empty($maxTitle) ? '...' : ''));
			
			$articleData->set('body',$articleBodyData->get('BodyValue'));
			$articleData->set('previewText', substr(strip_tags($articleBodyData->get('BodyValue')), 0, $maxText).( ! empty($maxText) ? '...' : ''));
			$articleData->set('previewMediumText', substr(strip_tags($articleBodyData->get('BodyValue')), 0, $maxMediumText).( ! empty($maxMediumText) ? '...' : ''));
		}
		
		return $articleData;
	}
}