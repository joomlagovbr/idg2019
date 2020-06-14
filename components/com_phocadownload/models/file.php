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


class PhocaDownloadModelFile extends JModelLegacy
{
	var $_file 				= null;
	var $_category 			= null;
	var $_section			= null;
	var $_filename			= null;
	var $_directlink		= 0;

	function __construct() {
		
		$app	= JFactory::getApplication();
		
		parent::__construct();
		
		$this->setState('filter.language',$app->getLanguageFilter());
	}

	function getFile( $fileId, $limitstartUrl) {
		if (empty($this->_file)) {			
			$query			= $this->_getFileQuery( $fileId );
			$this->_file	= $this->_getList( $query, 0 , 1 );
			
			// Don't display file if user has no access
			// - - - - - - - - - - - - - - - 
			if (empty($this->_file)) {
				return null;
			} 
			
			if (isset($this->_file[0]->access)) {
				$app		= JFactory::getApplication();
				$user 		= JFactory::getUser();
				
				
				if (!in_array($this->_file[0]->access, $user->getAuthorisedViewLevels())) {
					//$app->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("Please login to download the file"));
					// Return URL
					$return	= 'index.php?option=com_phocadownload&view=file&id='.$this->_file[0]->id.':'.$this->_file[0]->alias. $limitstartUrl . '&Itemid='. $app->input->get('Itemid', 0, 'int');
					$returnUrl  	= 'index.php?option=com_users&view=login&return='.base64_encode($return);
					$app->redirect(JRoute::_($returnUrl, false), JText::_('COM_PHOCADOWNLOAD_PLEASE_LOGIN_DOWNLOAD_FILE'));
					return;
				}
			} else {
				return null;
			}
			// - - - - - - - - - - - - - - - -
		}
		return $this->_file;
	}
	
	function _getFileQuery( $fileId ) {
		
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user 		= JFactory::getUser();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());
		
		$pQ			= $params->get( 'enable_plugin_query', 0 );
		
		$categoryId	= 0;
		$category	= $this->getCategory($fileId);
		if (isset($category[0]->id)) {
			$categoryId = $category[0]->id;
		}
		
		$wheres[]	= " c.catid= ".(int) $categoryId;
		$wheres[]	= " c.catid= cc.id";
		$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND c.access IN ('.$userLevels.') ) )';
		$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND cc.access IN ('.$userLevels.') ) )';
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
		
		$query = ' SELECT c.*, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias, cc.access as cataccess, cc.accessuserid as cataccessuserid, lc.title AS licensetitle, lc.description AS licensetext, lc.id AS licenseid'
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
		$wheres[] = " cc.access IN (".$userLevels.")";
		$wheres[] = " cc.published = 1";
		
		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}
		
		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			JPluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = array();
			$results = \JFactory::getApplication()->triggerEvent('onGetCategory', array (&$wheres, &$joins, $fileId,  $params));	
			// END GWE MOD
		}
		
		$query = " SELECT cc.id, cc.title, cc.alias, cc.description, cc.access as cataccess, cc.accessuserid as cataccessuserid"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload AS c ON c.catid = cc.id"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY cc.ordering";
				
				
		return $query;
	}
	
}
?>