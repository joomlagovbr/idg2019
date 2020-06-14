<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
class PhocaDownloadExternal
{
	public static function checkOSE($fileName) {
		if (file_exists(JPATH_SITE.'/components/com_osemsc/init.php') 
		&& file_exists(JPATH_ADMINISTRATOR.'/components/com_ose_cpu/define.php')) {
            require_once(JPATH_SITE.'components/com_osemsc/init.php');
            oseRegistry :: call('content')->checkAccess('phoca', 'category', $fileName->catid);
        } else if (file_exists(JPATH_ADMINISTRATOR . "/components/com_osemsc/warehouse/api.php")) {
            require_once (JPATH_ADMINISTRATOR . "/components/com_osemsc/warehouse/api.php");
            $checkmsc = new OSEMSCAPI();
            $checkmsc->ACLCheck("phoca", "cat", $fileName->catid, true);
        }
	}
}
?>