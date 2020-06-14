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

class PhocaDownloadCpModelPhocaDownloadLinkFile extends JModelLegacy
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
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
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
		//$search				= J String::strtolower( $search );
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