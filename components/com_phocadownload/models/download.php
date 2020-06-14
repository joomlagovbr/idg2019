<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.model');


class PhocaDownloadModelDownload extends JModelLegacy
{
	var $_file 				= null;
	var $_category 			= null;
	var $_filename			= null;
	var $_directlink		= 0;

	function __construct() {
		$app	= JFactory::getApplication();
		parent::__construct();
		$this->setState('filter.language',$app->getLanguageFilter());
	}

	function getFile( $downloadToken) {
		if (empty($this->_file)) {			
			$query			= $this->_getFileQuery( $downloadToken);
			$this->_file	= $this->_getList( $query, 0 , 1 );
			
			// Don't display file if user has no access
			// - - - - - - - - - - - - - - - 
			if (empty($this->_file)) {
				return null;
			} 
			// - - - - - - - - - - - - - - - -
		}
		return $this->_file;
	}
	
	function _getFileQuery( $downloadToken ) {
		
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user 		= JFactory::getUser();
		
		$pQ			= $params->get( 'enable_plugin_query', 0 );
		
		$wheres[] = " c.approved = 1";
		$wheres[] = " c.published = 1";
		$wheres[] = " c.token = " . $this->_db->Quote($downloadToken);
		
		
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
		
		$query = ' SELECT c.*, lc.title AS licensetitle, lc.description AS licensetext, lc.id AS licenseid'
				.' FROM #__phocadownload AS c' 
				.' LEFT JOIN #__phocadownload_licenses AS lc ON lc.id = c.confirm_license'
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				.' WHERE ' . implode( ' AND ', $wheres )
				.' ORDER BY c.ordering';
				
		return $query;
	}
}
?>