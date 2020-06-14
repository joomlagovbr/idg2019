<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die();
jimport('joomla.application.component.controller');
$app		= JFactory::getApplication();
$option 	= $app->input->get('option');

$l['cp']		= array('COM_PHOCADOWNLOAD_CONTROL_PANEL', '');
$l['f']			= array('COM_PHOCADOWNLOAD_FILES', 'phocadownloadfiles');
$l['c']			= array('COM_PHOCADOWNLOAD_CATEGORIES', 'phocadownloadcats');
$l['l']			= array('COM_PHOCADOWNLOAD_LICENSES', 'phocadownloadlics');
$l['st']		= array('COM_PHOCADOWNLOAD_STATISTICS', 'phocadownloadstat');
$l['d']			= array('COM_PHOCADOWNLOAD_DOWNLOADS', 'phocadownloaddownloads');
$l['u']			= array('COM_PHOCADOWNLOAD_UPLOADS', 'phocadownloaduploads');
$l['fr']		= array('COM_PHOCADOWNLOAD_FILE_RATING', 'phocadownloadrafile');
$l['t']			= array('COM_PHOCADOWNLOAD_TAGS', 'phocadownloadtags');
$l['ly']		= array('COM_PHOCADOWNLOAD_LAYOUT', 'phocadownloadlayouts');
$l['sty']		= array('COM_PHOCADOWNLOAD_STYLES', 'phocadownloadstyles');
$l['log']		= array('COM_PHOCADOWNLOAD_LOGGING', 'phocadownloadlogs');
$l['in']		= array('COM_PHOCADOWNLOAD_INFO', 'phocadownloadinfo');

// Submenu view
//$view	= JFactory::getApplication()->input->get( 'view', '', '', 'string', J R EQUEST_ALLOWRAW );
//$layout	= JFactory::getApplication()->input->get( 'layout', '', '', 'string', J R EQUEST_ALLOWRAW );
$view	= JFactory::getApplication()->input->get('view');
$layout	= JFactory::getApplication()->input->get('layout');

if ($layout == 'edit') {
} else {
	foreach ($l as $k => $v) {
		
		if ($v[1] == '') {
			$link = 'index.php?option='.$option;
		} else {
			$link = 'index.php?option='.$option.'&view=';
		}

		if ($view == $v[1]) {
			JHtmlSidebar::addEntry(JText::_($v[0]), $link.$v[1], true );
		} else {
			JHtmlSidebar::addEntry(JText::_($v[0]), $link.$v[1]);
		}
	}
}

class PhocadownloadCpController extends JControllerLegacy {
	function display($cachable = false, $urlparams = array()) {
		parent::display($cachable , $urlparams);
	}
}
?>
