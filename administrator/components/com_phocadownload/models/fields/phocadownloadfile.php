<?php
/*
* @package      Joomla.Framework
* @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
* @license      GNU General Public License version 2 or later; see LICENSE.txt
*
* @component Phoca Component
* @copyright Copyright (C) Jan Pavelka www.phoca.cz
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
*/
defined('_JEXEC') or die();

class JFormFieldPhocaDownloadFile extends JFormField
{
   protected $type = 'PhocaDownloadFile';

   protected function getInput() {
   
      $db = JFactory::getDBO();

       //build the list of files
      $query = 'SELECT a.title , a.id , a.catid'
      . ' FROM #__phocadownload AS a'
      . ' WHERE a.published = 1'
      . ' ORDER BY a.ordering';
      $db->setQuery( $query );

      $messages = $db->loadObjectList();
      $options = array();
      if ($messages)
      {
         foreach($messages as $message) 
         {
            $options[] = JHtml::_('select.option', $message->id, $message->title);
         }
      }
	  
	  $attr = '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= ' class="inputbox"';
		
      array_unshift($options, JHTML::_('select.option', '', '- '.JText::_('COM_PHOCADOWNLOAD_SELECT_FILE').' -', 'value', 'text'));
      return JHTML::_('select.genericlist',  $options,  $this->name, trim($attr), 'value', 'text', $this->value, $this->id );

   }
}
?>