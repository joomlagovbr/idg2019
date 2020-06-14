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


class PhocaDownloadModelRatingFileA extends JModelLegacy
{
	
	function rate($data) {
		$row = $this->getTable('phocadownloadfilevotes');
		
		if (!$row->bind($data)) {
			throw new Exception($this->_db->getErrorMsg(), 500);
			return false;
		}

		$row->date 		= gmdate('Y-m-d H:i:s');

		$row->published = 1;

		if (!$row->id) {
			$where = 'fileid = ' . (int) $row->fileid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		if (!$row->check()) {
			throw new Exception($this->_db->getErrorMsg(), 500);
			return false;
		}

		if (!$row->store()) {
			throw new Exception($this->_db->getErrorMsg(), 500);
			return false;
		}
		
		// Update the Vote Statistics
		if (!PhocaDownloadRate::updateVoteStatisticsFile( $data['fileid'])) {
			return false;
		}
		
		return true;
	}
}
?>
