<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

class PhocaDownloadCpModelPhocaDownloadLogs extends JModelList
{

	protected	$option 		= 'com_phocadownload';
	public 		$context		= 'com_phocadownload.phocadownloadlogs';
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'date', 'a.date',
				'username', 'ua.username',
				'category_id', 'category_id',
				'file_id', 'f.id',
				'file_title', 'f.title',
				'filename', 'f.filename',
				'ip', 'a.ip',
				'page', 'a.page',
				'type', 'a.type'

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
/*
		$accessId = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);
*/
		//$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
	//	$this->setState('filter.state', $state);
/*
		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null);
		$this->setState('filter.category_id', $categoryId);

		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);
*/

		$type = $app->getUserStateFromRequest($this->context.'.filter.type', 'filter_type', '', 'string');
		$this->setState('filter.type', $type);
		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocadownload');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.date', 'asc');
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.type');
		//$id	.= ':'.$this->getState('filter.access');
		//$id	.= ':'.$this->getState('filter.state');

		return parent::getStoreId($id);
	}
	
	protected function getListQuery()
	{

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
		$query->from('#__phocadownload_logging AS a');

		// Join over the language
		//$query->select('l.title AS language_title');
		//$query->join('LEFT', '#__languages AS l ON l.lang_code = a.language');
		
		
		$query->select('f.id as file_id, f.title as file_title, f.filename as filename');
		$query->join('LEFT', '#__phocadownload AS f ON f.id = a.fileid');
		
		$query->select('cc.id as category_id');
		$query->join('LEFT', '#__phocadownload_categories AS cc ON cc.id = f.catid');

		// Join over the users for the checked out user.
		//$query->select('ua.id AS userid, ua.username AS username, ua.name AS usernameno');
		//$query->join('LEFT', '#__users AS ua ON ua.id=a.userid');
		
		$query->select('ua.id AS userid, ua.username AS username, ua.name AS usernameno');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.userid');
		
		//$query->select('uc.name AS editor');
		//$query->join('LEFT', '#__users AS uc ON uc.id=f.checked_out');
		

			


/*		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
*/

		// Filter by access level.
	/*	if ($access = $this->getState('filter.access')) {
			$query->where('a.access = '.(int) $access);
		}*/

		// Filter by published state.
		/*$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}*/
		
		
		// Filter by published type.
		$type = $this->getState('filter.type');
		
		if (is_numeric($type)) {
			$query->where('a.type = '.(int) $type);
			
		}
		else if ($type === '') {
			$query->where('(a.type IN (1, 2))');
			
		}

		
		// Filter by category.
		/*$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = ' . (int) $categoryId);
		}*/

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
				$query->where('( ua.username LIKE '.$search.' OR ua.name LIKE '.$search.' OR f.title LIKE '.$search.' OR f.filename LIKE '.$search.' or a.ip LIKE '.$search.')');
			}
		}
		
		//$query->group('a.id');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'date');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
	
		if ($orderCol == 'a.id' || $orderCol == 'username') {
			$orderCol = 'username '.$orderDirn.', a.id';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__', 'jos_', $query->__toString()));
		return $query;
	}
}
?>