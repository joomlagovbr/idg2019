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
jimport( 'joomla.application.component.view' );

class phocaDownloadCpViewphocaDownloadLinkYtb extends JViewLegacy
{
	protected $t;

	function display($tpl = null) {
		$app	= JFactory::getApplication();
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');
		JHtml::_('behavior.keepalive');
		JHtml::_('formbehavior.chosen', 'select');
		$document	= JFactory::getDocument();
		$uri		= \Joomla\CMS\Uri\Uri::getInstance();
		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = JURI::base();

		}
		JHTML::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );

		$eName				= JFactory::getApplication()->input->get('e_name');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinks&amp;tmpl=component&amp;e_name='.$this->t['ename'];

		//$this->assignRef('tmpl',	$this->t);
		parent::display($tpl);
	}
}
?>
