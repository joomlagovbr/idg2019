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

class PhocaDownloadModelPhocaDownloadLinkCat extends JModelLegacy
{
	//var $_data_sec;
	var $_data_cat;
	
	function __construct() {
		parent::__construct();
	}
/*
	function &getDataSec() {
		if ($this->_loadDataSec()) {
			
		} else {
			$this->_initDataSec();
		}
		return $this->_data_sec;
	}*/
	
	function &getDataCat($sectionList) {
		if ($this->_loadDataCat($sectionList)) {
			
		} else {
			$this->_initDataCat();
		}
		return $this->_data_cat;
	}
	/*
	function _loadDataSec() {
		if (empty($this->_data_sec)) {
			$query = 'SELECT s.id, s.title'
			. ' FROM #__phocadownload_sections AS s'
			. ' WHERE s.published = 1'
			. ' ORDER BY s.ordering';
			$this->_db->setQuery($query);
			$this->_data_sec = $this->_db->loadObjectList();
			return (boolean) $this->_data_sec;
		}
		return true;
	}*/
	/*
	function _loadDataCat($sectionList) {
		if (empty($this->_data_cat)) {
			$query = 'SELECT c.id, c.title, c.section' 
				.' FROM #__phocadownload_categories AS c' 
				.' WHERE c.section IN ( \''.$sectionList.'\' )'
				.' AND c.published = 1'				
				.' ORDER BY c.ordering';
			$this->_db->setQuery($query);
			$this->_data_cat = $this->_db->loadObjectList();
			return (boolean) $this->_data_cat;
		}
		return true;
	}*/
	/*
	function _initDataSec() {
		if (empty($this->_data_sec)) {
			return (boolean) array();
		}
		return true;
	}*/
	function _initDataCat() {
		if (empty($this->_data_cat)) {
			return (boolean) array();
		}
		return true;
	}	
}
?>