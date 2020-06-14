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

class PhocaDownloadModelPlay extends JModelLegacy
{
	var $_file 				= null;
	var $_category 			= null;
	var $_section			= null;
	var $_filename			= null;

	function __construct() {
		
		$app	= JFactory::getApplication();
		parent::__construct();
		$this->setState('filter.language',$app->getLanguageFilter());
		
	}

	function getFile( $fileId) {		
		if (empty($this->_file)) {	
			$query			= $this->_getFileQuery( $fileId );
			$this->_file	= $this->_getList( $query, 0 , 1 );
			
		/*	// Don't display file if user has no access
			// - - - - - - - - - - - - - - - 
			if (empty($this->_file)) {
				return null;
			} 
			
			if (isset($this->_file[0]->access)) {
				if ($aid !== null) {
					if ($this->_file[0]->access > (int) $aid) {
							$app->redirect(JRoute::_('index.php?option=com_users&view=login', false), JText::_("Please login to download the file"));
							exit;
					}
				} else {
					return null;
				}
			} else {
				return null;
			}
			// - - - - - - - - - - - - - - - -*/
		}
		return $this->_file;
	}
	
	function _getFileQuery( $fileId ) {
		
		$app			= JFactory::getApplication();
		$params 		= $app->getParams();
		$categoryId		= 0;
		$category		= $this->getCategory($fileId);
		if (isset($category[0]->id)) {
			$categoryId = $category[0]->id;
		}
		
		$pQ			= $params->get( 'enable_plugin_query', 0 );
		
		$wheres[]	= " c.catid= ".(int) $categoryId;
		$wheres[]	= " c.catid= cc.id";
		/*if ($aid !== null) {
		
			// Should be not displayed, only in case user will add direct url
			// IF unaccessible file = 1 then display unaccessible file for all
			// IF unaccessible file = 0 then display it only for them who have access to this file
			$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND c.access <= ' . (int) $aid.') )';
			$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND cc.access <= ' . (int) $aid.') )';
			//$wheres[] = "c.access <= " . (int) $aid;
			//$wheres[] = "cc.access <= " . (int) $aid;
		}*/
		$wheres[] = " c.published = 1";
		$wheres[] = " c.approved = 1";
		$wheres[] = " cc.published = 1";
		
		$wheres[] = " c.id = " . (int) $fileId;
		
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
			$results = \JFactory::getApplication()->triggerEvent('onGetFile', array (&$wheres, &$joins, $fileId,  $params));	
			// END GWE MOD
		}
		
		$query = ' SELECT c.*, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias, cc.access as cataccess, cc.accessuserid as cataccessuserid, lc.title AS licensetitle, lc.description AS licensetext'
				.' FROM #__phocadownload AS c' 
				.' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = c.catid'
				.' LEFT JOIN #__phocadownload_licenses AS lc ON lc.id = c.confirm_license'
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				.' WHERE ' . implode( ' AND ', $wheres )
				.' ORDER BY c.ordering';
				
		return $query;
	}
	
	function getCategory($fileId) {
		
		if (empty($this->_category)) {				
			$query			= $this->_getCategoryQuery( $fileId );
			$this->_category= $this->_getList( $query, 0, 1 );
		}
		return $this->_category;
	}
	
	function _getCategoryQuery( $fileId ) {
		
		$wheres		= array();
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user 		= JFactory::getUser();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());
		
		$pQ			= $params->get( 'enable_plugin_query', 0 );
		
		$wheres[]	= " c.id= ".(int)$fileId;
		$wheres[] 	= " cc.access IN (".$userLevels.")";
		$wheres[] 	= " cc.published = 1";
		
		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			JPluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = array();
			$results = \JFactory::getApplication()->triggerEvent('onGetCategory',  array (&$wheres, &$joins,$categoryId,  $params));		
			// END GWE MOD
		}
		
		$query = " SELECT cc.id, cc.title, cc.alias, cc.image, cc.image_position, cc.description"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload AS c ON c.catid = cc.id"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY cc.ordering";
		return $query;
	}
	
}
?>