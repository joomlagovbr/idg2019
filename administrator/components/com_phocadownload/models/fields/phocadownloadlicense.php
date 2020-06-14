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

class JFormFieldPhocaDownloadLicense extends JFormField
{
	protected $type 		= 'PhocaDownloadLicense';

	protected function getInput() {
		
		$db = JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value'
		. ' FROM #__phocadownload_licenses AS a'
		//. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$licenses 	= $db->loadObjectList();

		$id 		= $this->form->getValue('id'); // id of current license
		$required	= ((string) $this->element['required'] == 'true') ? TRUE : FALSE;
		
		array_unshift($licenses, JHTML::_('select.option', '', '- '.JText::_('COM_PHOCADOWNLOAD_SELECT_LICENSE').' -', 'value', 'text'));
		
		return JHTML::_('select.genericlist',  $licenses,  $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );
	}
}
?>