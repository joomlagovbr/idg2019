<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.model');


class PhocaDownloadModelCategories extends JModelLegacy
{
	var $_categories 			= null;
	var $_most_viewed_docs 		= null;
	var $_categories_ordering	= null;
	var $_category_ordering		= null;

	function __construct() {
		$app	= JFactory::getApplication();
		parent::__construct();

		$this->setState('filter.language',$app->getLanguageFilter());
	}

	function getCategoriesList() {
		if (empty($this->_categories)) {
			$query				= $this->_getCategoriesListQuery();

			//$this->_categories 	= $this->_getList( $query );
			$categories 	= $this->_getList( $query );

			if (!empty($categories)) {

				// Parent Only
				foreach ($categories as $k => $v) {
					if ($v->parent_id == 0) {
						$this->_categories[$v->id] = $categories[$k];
					}
				}

				// Subcategories
				foreach ($categories as $k => $v) {
					if (isset($this->_categories[$v->parent_id])) {
						$this->_categories[$v->parent_id]->subcategories[] = $categories[$k];
						$this->_categories[$v->parent_id]->numsubcat++;
					}
				}
			}
			/*
			$this->categories 	= $this->_getList( $query );
			if (!empty($this->categories)) {
				foreach ($this->categories as $key => $value) {
					$query	= $this->getCategoriesListQuery( $value->id, $categoriesOrdering );
					$this->categories[$key]->subcategories = $this->_getList( $query );
				}
			}*/

		}

		return $this->_categories;
	}



	/*
	 * Get only parent categories
	 */
	function _getCategoriesListQuery(  ) {

		$wheres		= array();
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user 		= JFactory::getUser();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());


		$pQ					= $params->get( 'enable_plugin_query', 0 );
		$display_categories = $params->get('display_categories', '');
		$hide_categories 	= $params->get('hide_categoriess', '');

		if ( $display_categories != '' ) {
			$wheres[] = " cc.id IN (".$display_categories.")";
		}

		if ( $hide_categories != '' ) {
			$wheres[] = " cc.id NOT IN (".$hide_categories.")";
		}
		//$wheres[] = " cc.parent_id = 0";
		$wheres[] = " cc.published = 1";
		$wheres[] = " cc.access IN (".$userLevels.")";

		if ($this->getState('filter.language')) {
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		$categoriesOrdering = $this->_getCategoryOrdering();

		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			JPluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = array();
			$results = \JFactory::getApplication()->triggerEvent('onGetCategoriesList', array (&$wheres, &$joins,  $params));
			// END GWE MOD
		}

		$query =  " SELECT cc.id, cc.parent_id, cc.title, cc.alias, cc.image, cc.access, cc.description, cc.accessuserid, COUNT(c.id) AS numdoc, 0 AS numsubcat"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload AS c ON c.catid = cc.id AND c.published = 1  AND c.textonly = 0"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " GROUP BY cc.id, cc.parent_id, cc.title, cc.image, cc.alias, cc.access, cc.description, cc.accessuserid"
				. " ORDER BY ".$categoriesOrdering;

		return $query;
	}


	/*
	 * Get only first level under parent categories
	 */
	function _getCategoryListQuery( $parentCatId ) {

		$wheres		= array();
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user 		= JFactory::getUser();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());

		$pQ					= $params->get( 'enable_plugin_query', 0 );
		$display_categories = $params->get('display_categories', '');
		$hide_categories 	= $params->get('hide_categoriess', '');

		if ( $display_categories != '' ) {
			$wheres[] = " cc.id IN (".$display_categories.")";
		}

		if ( $hide_categories != '' ) {
			$wheres[] = " cc.id NOT IN (".$hide_categories.")";
		}
		$wheres[] = " cc.parent_id = ".(int)$parentCatId;
		$wheres[] = " cc.published = 1";
		$wheres[] = " cc.access IN (".$userLevels.")";

		if ($this->getState('filter.language')) {
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		$categoryOrdering = $this->_getCategoryOrdering();

		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			JPluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = array();
			$results = \JFactory::getApplication()->triggerEvent('onGetCategoryList', array (&$wheres, &$joins,  $params));
			// END GWE MOD
		}

		$query = " SELECT  cc.id, cc.title, cc.alias, cc.image, cc.access, cc.accessuserid, COUNT(c.id) AS numdoc"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload AS c ON c.catid = cc.id AND c.published = 1  AND c.textonly = 0"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " GROUP BY cc.id, cc.title, cc.alias, cc.image, cc.access, cc.accessuserid"
				. " ORDER BY ".$categoryOrdering;

		return $query;


	}

	function getMostViewedDocsList() {

		if (empty($this->_most_viewed_docs)) {
			$query						= $this->_getMostViewedDocsListQuery();
			$this->_most_viewed_docs 	= $this->_getList( $query );
		}
		return $this->_most_viewed_docs;
	}

	function _getMostViewedDocsListQuery() {

		$wheres		= array();
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user 		= JFactory::getUser();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());

		$pQ						= $params->get( 'enable_plugin_query', 0 );
		$most_viewed_docs_num 	= $params->get( 'most_download_files_num', 5 );
		$display_categories 	= $params->get('display_categories', '');
		$hide_categories 		= $params->get('hide_categoriess', '');

		if ( $display_categories != '' ) {
			$wheres[] = " cc.id IN (".$display_categories.")";
		}

		if ( $hide_categories != '' ) {
			$wheres[] = " cc.id NOT IN (".$hide_categories.")";
		}


		$wheres[]	= " c.catid= cc.id";
		$wheres[]	= " c.published= 1";
		$wheres[]	= " c.approved= 1";
		$wheres[]	= " c.textonly= 0";
		$wheres[] 	= " cc.access IN (".$userLevels.")";
		$wheres[] 	= " c.access IN (".$userLevels.")";


		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		// Active
		$jnow		= JFactory::getDate();
		$now		= $jnow->toSql();
		$nullDate	= $this->_db->getNullDate();
		$wheres[] = ' ( c.publish_up = '.$this->_db->Quote($nullDate).' OR c.publish_up <= '.$this->_db->Quote($now).' )';
		$wheres[] = ' ( c.publish_down = '.$this->_db->Quote($nullDate).' OR c.publish_down >= '.$this->_db->Quote($now).' )';



		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			JPluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = array();
			$results = \JFactory::getApplication()->triggerEvent('onGetMostViewedDocs', array (&$wheres, &$joins, 0, $params));
			// END GWE MOD
		}

		$query = " SELECT c.id, c.title, c.alias, c.filename, c.date, c.hits, c.image_filename, cc.id AS categoryid, cc.access as cataccess, cc.accessuserid as cataccessuserid, cc.title AS categorytitle, cc.alias AS categoryalias "
				." FROM #__phocadownload AS c, #__phocadownload_categories AS cc"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY c.hits DESC"
				. " LIMIT ".(int)$most_viewed_docs_num;
		return $query;
	}

	function _getCategoryOrdering() {
		if (empty($this->_category_ordering)) {

			$app						= JFactory::getApplication();
			$params 					= $app->getParams();
			$ordering					= $params->get( 'category_ordering', 1 );
			$this->_category_ordering 	= PhocaDownloadOrdering::getOrderingText($ordering, 2);

		}
		return $this->_category_ordering;
	}
}
?>
