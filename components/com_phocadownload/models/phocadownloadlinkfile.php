<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
use Joomla\String\StringHelper;

// CUSTOM PAGINATON
class PhocaDownloadViewPhocaDownloadLinkFilePagination extends JPagination
{
	protected function _buildDataObject()
	{
		$data = new stdClass;
		
		$uri 					= \Joomla\CMS\Uri\Uri::getInstance();
		$uriS					= $uri->toString();

		// Build the additional URL parameters string.
		$params = '';

		if (!empty($this->additionalUrlParams))
		{
			foreach ($this->additionalUrlParams as $key => $value)
			{
				$params .= '&' . $key . '=' . $value;
			}
		}

		$data->all = new JPaginationObject(JText::_('JLIB_HTML_VIEW_ALL'), $this->prefix);

		if (!$this->viewall)
		{
			$data->all->base = '0';
			$data->all->link = $uriS . '' .$params . '&' . $this->prefix . 'limitstart=';
		}

		// Set the start and previous data objects.
		$data->start = new JPaginationObject(JText::_('JLIB_HTML_START'), $this->prefix);
		$data->previous = new JPaginationObject(JText::_('JPREV'), $this->prefix);

		if ($this->pagesCurrent > 1)
		{
			$page = ($this->pagesCurrent - 2) * $this->limit;

			// Set the empty for removal from route
			// @to do remove code: $page = $page == 0 ? '' : $page;

			
			
			$data->start->base = '0';
			$data->start->link = $uriS . '' . $params . '&' . $this->prefix . 'limitstart=0';
			$data->previous->base = $page;
			$data->previous->link = $uriS . '' .$params . '&' . $this->prefix . 'limitstart=' . $page;
			
			
			
		}

		// Set the next and end data objects.
		$data->next = new JPaginationObject(JText::_('JNEXT'), $this->prefix);
		$data->end = new JPaginationObject(JText::_('JLIB_HTML_END'), $this->prefix);

		if ($this->pagesCurrent < $this->pagesTotal)
		{
			$next = $this->pagesCurrent * $this->limit;
			$end = ($this->pagesTotal - 1) * $this->limit;

			$data->next->base = $next;
			$data->next->link = $uriS . '' .$params . '&' . $this->prefix . 'limitstart=' . $next;
			$data->end->base = $end;
			$data->end->link = $uriS . '' .$params . '&' . $this->prefix . 'limitstart=' . $end;
		}

		$data->pages = array();
		$stop = $this->pagesStop;

		for ($i = $this->pagesStart; $i <= $stop; $i++)
		{
			$offset = ($i - 1) * $this->limit;

			$data->pages[$i] = new JPaginationObject($i, $this->prefix);

			if ($i != $this->pagesCurrent || $this->viewall)
			{
				$data->pages[$i]->base = $offset;
				$data->pages[$i]->link = $uriS . '' .$params . '&' . $this->prefix . 'limitstart=' . $offset;
			}
			else
			{
				$data->pages[$i]->active = true;
			}
		}

		return $data;
	}
}

class PhocaDownloadModelPhocaDownloadLinkFile extends JModelLegacy
{
	var $_data 			= null;
	var $_total 		= null;
	var $_pagination 	= null;
	var $_context		= 'com_phocadownload.phocadownloadlinkfile';

	function __construct() {
		parent::__construct();		
		$app = JFactory::getApplication();
		// Get the pagination request variables
		$limit	= $app->getUserStateFromRequest( $this->_context.'.list.limit', 'limit', $app->get('list_limit'), 'int' );
		$limitstart	= $app->getUserStateFromRequest( $this->_context.'.limitstart', 'limitstart',	0, 'int' );
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData() {
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new PhocaDownloadViewPhocaDownloadLinkFilePagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	
	function _buildQuery() {
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT a.*, cc.title AS categorytitle, ag.title AS access_level '
			. ' FROM #__phocadownload AS a '
			. ' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = a.catid '
			//. ' LEFT JOIN #__phocadownload_sections AS s ON s.id = a.sectionid '
			. ' LEFT JOIN #__viewlevels AS ag ON ag.id = a.access '
			. ' LEFT JOIN #__users AS u ON u.id = a.checked_out '
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentOrderBy() {
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',	'a.ordering','cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY categorytitle, a.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', categorytitle, a.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere() {
		$app = JFactory::getApplication();
		$filter_state		= $app->getUserStateFromRequest( $this->_context.'.filter_state',	'filter_state',	'',	'word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context.'.catid','catid',0,	'int' );
		//$filter_sectionid	= $app->getUserStateFromRequest( $this->_context.'.filter_sectionid',	'filter_sectionid',	0,	'int' );
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',	'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$search				= $app->getUserStateFromRequest( $this->_context.'.search','search','','string' );
		$search				= StringHelper::strtolower( $search );

		$where = array();

		if ($filter_catid > 0) {
			$where[] = 'a.catid = '.(int) $filter_catid;
		}
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'a.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}
		
		
		$where[] = 'a.published = 1';
		$where[] = 'a.approved 	= 1';
		$where[] = 'a.textonly <> 1';
		
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
}
?>