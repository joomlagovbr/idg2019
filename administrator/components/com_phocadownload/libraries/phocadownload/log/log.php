<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaDownloadLog
{

	public static function log($fileid, $type = 1) {

		$paramsC 	= JComponentHelper::getParams('com_phocadownload');
		$logging	= $paramsC->get('enable_logging', 0);
		// No Logging
		if ($logging == 0) {
			return false;
		}

		// Only Downloads
		if ($logging == 1 && $type == 2) {
			return false;
		}

		// Only Uploads
		if ($logging == 2 && $type == 1) {
			return false;
		}


		$user 	= JFactory::getUser();
		$uri 	= \Joomla\CMS\Uri\Uri::getInstance();
		$db 	= JFactory::getDBO();

		$row 	= JTable::getInstance('PhocaDownloadLogging', 'Table');
		$data					= array();
		$data['type']			= (int)$type;
		$data['fileid']			= (int)$fileid;
		$data['catid']			= 0;// Don't stored catid, bind the catid while displaying log
		$data['userid']			= (int)$user->id;
		$data['ip']	=			$_SERVER["REMOTE_ADDR"];
		$data['page']			= $uri->toString();


		if (!$row->bind($data)) {
			throw new Exception($db->getErrorMsg(), 500);
			return false;
		}

		$jnow		= JFactory::getDate();
		$row->date	= $jnow->toSql();

		if (!$row->check()) {
			throw new Exception($db->getErrorMsg(), 500);
			return false;
		}

		if (!$row->store()) {
			throw new Exception($db->getErrorMsg(), 500);
			return false;
		}
		return true;
	}
}
?>
