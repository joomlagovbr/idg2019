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
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.modellist' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );


class PhocaDownloadCpModelPhocaDownloadstat extends JModelList
{
	protected	$option 		= 'com_phocadownload';
	public 		$typeAlias 		= 'com_phocadownload.phocadownloadstat';
	
	
	public function __construct($config = array())
   {
      if (empty($config['filter_fields'])) {
         $config['filter_fields'] = array(
            'id', 'a.id',
			'hits', 'a.hits',
            'filename', 'a.filename',
            'title', 'a.title',
         );
      }

      parent::__construct($config);
   }
	
	protected function populateState($ordering = NULL, $direction = NULL)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		

		$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		
		$this->setState('filter.state', $state);

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null);
		$this->setState('filter.category_id', $categoryId);



		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocadownload');
		$this->setState('params', $params);

		// List state information.
		//parent::populateState('uc.name', 'asc');
		parent::populateState('a.hits', 'desc');
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.category_id');
		$id	.= ':'.$this->getState('filter.file_id');

		return parent::getStoreId($id);
	}
	
	
	protected function getListQuery()
	{
		/*
		$query = ' SELECT a.*, cc.title AS categorytitle, s.title AS sectiontitle, u.name AS editor, g.name AS groupname, MAX(a.hits) AS maxhit '
			. ' FROM #__phocadownload AS a '
			. ' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = a.catid '
			. ' LEFT JOIN #__phocadownload_sections AS s ON s.id = a.sectionid '
			. ' LEFT JOIN #__groups AS g ON g.id = a.access '
			. ' LEFT JOIN #__users AS u ON u.id = a.checked_out '
			. $where
			. ' GROUP by a.id'
			. $orderby
		*/
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__phocadownload` AS a');

		// Join over the language
		//$query->select('l.title AS language_title');
		//$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		
		
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
	

		// Join over the asset groups.
		//$query->select('ag.title AS access_level');
		//$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('c.title AS category_title, c.id AS category_id');
		$query->join('LEFT', '#__phocadownload_categories AS c ON c.id = a.catid');
		
		//$query->select('ua.id AS userid, ua.username AS username, ua.name AS usernameno');
		//$query->join('LEFT', '#__users AS ua ON ua.id = a.owner_id');
		
		//$query->select('v.average AS ratingavg');
		//$query->join('LEFT', '#__phocadownload_img_votes_statistics AS v ON v.imgid = a.id');

		// Filter by access level.
		//if ($access = $this->getState('filter.access')) {
		//	$query->where('a.access = '.(int) $access);
		//}

		// Filter by published state.
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = ' . (int) $categoryId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('( a.title LIKE '.$search.' OR a.filename LIKE '.$search.')');
			}
		}
		
		//$query->group('a.id');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = 'category_title '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__', 'jos_', $query->__toString()));
		return $query;
	}
	
	function getMaxAndSum() {
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select(
			$this->getState(
				'list.select',
				'MAX(a.hits) AS maxhit, SUM(a.hits) AS sumhit'
			)
		);
		
		$query->from('`#__phocadownload` AS a');

		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		//$query->select('c.title AS category_title, c.id AS category_id');
		//$query->join('LEFT', '#__phocadownload_categories AS c ON c.id = a.catid');

		// Filter by published state.
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = ' . (int) $categoryId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('a.title LIKE '.$search.' OR a.filename LIKE '.$search);
			}
		}
		
		$query->group('a.id, uc.name');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = 'category_title '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		$db->setQuery($query, 0, 1);
		$maxAndSum = $db->loadObject();
		
		//echo nl2br(str_replace('#__', 'jos_', $query->__toString()));
		return $maxAndSum;
	
	}
	
	
}
?>