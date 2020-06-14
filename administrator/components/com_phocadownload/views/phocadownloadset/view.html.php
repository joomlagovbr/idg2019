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

class PhocaDownloadCpViewPhocaDownloadset extends JViewLegacy
{
	function display($tpl = null) {


		$uri		= \Joomla\CMS\Uri\Uri::getInstance();
		$document	= JFactory::getDocument();
		$db		    = JFactory::getDBO();
		JHTML::stylesheet( 'phocadownload.css', 'administrator/components/com_phocadownload/assets/' );

		// Get data from the model
		$items		= & $this->get( 'Data');

		//$this->assignRef('items',		$items);
		$this->t['items'] = $items;
		//$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
		$this->_setToolbar();
	}

	function _setToolbar() {
		JToolbarHelper::title(   JText::_( 'Phoca Download Settings' ), 'settings.png' );
		JToolbarHelper::save();
		JToolbarHelper::apply();
		JToolbarHelper::cancel( 'cancel', 'Close' );
		JToolbarHelper::help( 'screen.phocadownload', true );
	}
}
?>
