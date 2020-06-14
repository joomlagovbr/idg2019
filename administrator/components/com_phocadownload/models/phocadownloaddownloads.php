<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.modellist' );

class PhocaDownloadCpModelPhocaDownloadDownloads extends JModelList
{
	protected	$option 		= 'com_phocadownload';

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'd.title',
				'alias', 'd.alias',
				'checked_out', 'd.checked_out',
				'checked_out_time', 'd.checked_out_time',
				'category_id', 'category_id',
				'usernameno', 'ua.usernameno',
				'username', 'ua.username',
				'ordering', 'a.ordering',

				'count', 'a.count',
				'date', 'a.date',

				'published','d.published',
				'filename', 'd.filename'

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

		$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $state);
*/
		$id = JFactory::getApplication()->input->get( 'id', '', '', 'int');
		if ((int)$id > 0) {
			$this->setState('filter.filestat_id', $id);
		} else {
			//$fileStatId = $app->getUserStateFromRequest($this->context.'.filter.filestat_id', 'filter_filestat_id', $id);
			$this->setState('filter.filestat_id', 0);
		}
/*
		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);*/

		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocadownload');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('username', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		/*$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.category_id');*/
		$id	.= ':'.$this->getState('filter.filestat_id');

		return parent::getStoreId($id);
	}


	protected function getListQuery()
	{
		/*$query = ' SELECT a.id, a.userid, a.fileid, d.filename AS filename, d.title AS filetitle, a.count, a.date, u.name AS uname, u.username AS username, 0 AS checked_out'
			. ' FROM #__phocadownload_user_stat AS a '
			. ' LEFT JOIN #__phocadownload AS d ON d.id = a.fileid '
			. ' LEFT JOIN #__users AS u ON u.id = a.userid '
			. $where
			. ' GROUP by a.id'
			. $orderby;
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
		$query->from('`#__phocadownload_user_stat` AS a');

		// Join over the language
		//$query->select('l.title AS language_title');
		//$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.


		//$query->select('uc.name AS editor');
		//$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');



		// Join over the asset groups.
		//$query->select('ag.title AS access_level');
		//$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('d.filename AS filename, d.title AS filetitle');
		$query->join('LEFT', '#__phocadownload AS d ON d.id = a.fileid');

		$query->select('ua.id AS userid, ua.username AS username, ua.name AS usernameno');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.userid');

		//$query->select('v.average AS ratingavg');
		//$query->join('LEFT', '#__phocadownload_img_votes_statistics AS v ON v.imgid = a.id');

		// Filter by access level.
		//if ($access = $this->getState('filter.access')) {
		//	$query->where('a.access = '.(int) $access);
		//}

		// Filter by published state.
		/*$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category.*/
		/*$fileStatId = $this->getState('filter.filestat_id');

		if (is_numeric($fileStatId)) {
			$query->where('a.fileid = ' . (int) $fileStatId);
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
				$query->where('( ua.username LIKE '.$search.' OR ua.name LIKE '.$search.' OR d.filename LIKE '.$search.' OR d.title LIKE '.$search.')');
			}
		}

	//	$query->group('a.id');

		// Add the list ordering clause.
		//$orderCol	= $this->state->get('list.ordering');
		//$orderDirn	= $this->state->get('list.direction');
		$orderCol	= $this->state->get('list.ordering', 'username');
		$orderDirn	= $this->state->get('list.direction', 'asc');

		if ($orderCol == 'a.id' || $orderCol == 'username') {
			$orderCol = 'ua.username '.$orderDirn.', a.id';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));


		return $query;

	}


	function reset($cid = array()) {
		if (count( $cid )) {
			\Joomla\Utilities\ArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$date = gmdate('Y-m-d H:i:s');
			//Delete it from DB
			$query = 'UPDATE #__phocadownload_user_stat'
					.' SET count = 0,'
					.' date = '.$this->_db->Quote($date)
					.' WHERE id IN ( '.$cids.' )';

			$this->_db->setQuery( $query );
			if(!$this->_db->execute()) {
				throw new Exception($this->_db->getErrorMsg(), 500);
				return false;
			}
		}
		return true;
	}
}
?>
