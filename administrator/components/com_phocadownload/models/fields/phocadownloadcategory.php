<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (! class_exists('PhocaDownloadCategory')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocadownload/libraries/phocadownload/category/category.php');
}

class JFormFieldPhocaDownloadCategory extends JFormField
{
	protected $type 		= 'PhocaDownloadCategory';

	protected function getInput() {

		$db = JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocadownload_categories AS a'
		//. ' WHERE a.published = 1' // don't lose information about category when it will be unpublished - you should still be able to edit file with such category in administration
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();


		$view 	= JFactory::getApplication()->input->get( 'view' );
		$catId	= -1;
		if ($view == 'phocadownloadcat') {
			$id 	= $this->form->getValue('id'); // id of current category
			if ((int)$id > 0) {
				$catId = $id;
			}
		}
		/*if ($view == 'phocadownloadfile') {
			$id 	= $this->form->getValue('catid'); // id of current category
			if ((int)$id > 0) {
				$catId = $id;
			}
		}*/



		//$required	= ((string) $this->element['required'] == 'true') ? TRUE : FALSE;
		$attr = '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= ' class="inputbox"';

		$tree = array();
		$text = '';
		$tree = PhocaDownloadCategory::CategoryTreeOption($data, $tree, 0, $text, $catId);

		//if ($required == TRUE) {

		//} else {

			array_unshift($tree, JHTML::_('select.option', '', '- '.JText::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -', 'value', 'text'));
		//}
		return JHTML::_('select.genericlist',  $tree,  $this->name, trim($attr), 'value', 'text', $this->value, $this->id );
	}
}
?>
