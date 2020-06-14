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

class PhocaDownloadCpModelPhocaDownloadLog extends JModelLegacy
{
	function __construct() {
		parent::__construct();
	}
	function reset() {
		//$user 	= JFactory::getUser();
		$query = 'TRUNCATE #__phocadownload_logging';
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			throw new Exception($this->_db->getErrorMsg(), 500);
			return false;
		}
		return true;
	}
}
?>