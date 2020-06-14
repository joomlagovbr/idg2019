<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');

$heading = '';
if ($this->t['p']->get( 'page_heading' ) != '') {
	$heading .= $this->t['p']->get( 'page_heading' );
}

if ($this->t['showpageheading'] != 0) {
	if ( $heading != '') {
	    echo '<h1>'. $this->escape($heading) . '</h1>';
	}
}
$tab = 0;
switch ($this->t['tab']) {
	case 'up':
		$tab = 1;
	break;

	case 'cc':
	default:
		$tab = 0;
	break;
}

echo '<div>&nbsp;</div>';

if ($this->t['displaytabs'] > 0) {
	echo '<div id="phocadownload-pane">';
	//$pane =& J Pane::getInstance('Tabs', array('startOffset'=> $this->t['tab']));
	//echo $pane->startPane( 'pane' );
	echo JHtml::_('tabs.start', 'config-tabs-com_phocadownload-user', array('useCookie'=>1, 'startOffset'=> $this->t['tab']));

	//echo $pane->startPanel( JHTML::_( 'image .site', $this->t['pi'].'icon-document-16.png','', '', '', '', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'files' );
	echo JHtml::_('tabs.panel', JHtml::_( 'image', $this->t['pi'].'icon-document-16.png', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'files' );
	echo $this->loadTemplate('files');
	//echo $pane->endPanel();

	//echo $pane->endPane();
	echo JHtml::_('tabs.end');
	echo '</div>';
}
echo PhocaDownloadUtils::getInfo();
?>
