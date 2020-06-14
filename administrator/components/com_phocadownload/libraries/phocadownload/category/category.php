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

class PhocaDownloadCategory
{
	public static function CategoryTreeOption($data, $tree, $id=0, $text='', $currentId) {		

		foreach ($data as $key) {	
			$show_text =  $text . $key->text;
			
			if ($key->parentid == $id && $currentId != $id && $currentId != $key->value) {
				$tree[$key->value] 			= new JObject();
				$tree[$key->value]->text 	= $show_text;
				$tree[$key->value]->value 	= $key->value;
				$tree = PhocaDownloadCategory::CategoryTreeOption($data, $tree, $key->value, $show_text . " - ", $currentId );	
			}	
		}
		return($tree);
	}

	public static function filterCategory($query, $active = NULL, $frontend = NULL, $onChange = TRUE, $fullTree = NULL ) {
		
		$db	= JFactory::getDBO();

		$form = 'adminForm';
		if ($frontend == 1) {
			$form = 'phocadownloadfilesform';
		}
		
		if ($onChange) {
			$onChO = 'class="inputbox" size="1" onchange="document.'.$form.'.submit( );"';
		} else {
			$onChO = 'class="inputbox" size="1"';
		}
		
		$categories[] = JHTML::_('select.option', '0', '- '.JText::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -');
		$db->setQuery($query);
		$catData = $db->loadObjectList();
		
		
		
		if ($fullTree) {
			
			// Start - remove in case there is a memory problem
			$tree = array();
			$text = '';
			
			$queryAll = ' SELECT cc.id AS value, cc.title AS text, cc.parent_id as parentid'
					.' FROM #__phocadownload_categories AS cc'
					.' ORDER BY cc.ordering';
			$db->setQuery($queryAll);
			$catDataAll 		= $db->loadObjectList();

			$catDataTree	= PhocaDownloadCategory::CategoryTreeOption($catDataAll, $tree, 0, $text, -1);
			
			$catDataTreeRights = array();
			/*foreach ($catData as $k => $v) {
				foreach ($catDataTree as $k2 => $v2) {
					if ($v->value == $v2->value) {
						$catDataTreeRights[$k]->text 	= $v2->text;
						$catDataTreeRights[$k]->value = $v2->value;
					}
				}
			}*/
			
			foreach ($catDataTree as $k => $v) {
                foreach ($catData as $k2 => $v2) {
                   if ($v->value == $v2->value) {
						$catDataTreeRights[$k] = new StdClass();
						$catDataTreeRights[$k]->text  = $v->text;
						$catDataTreeRights[$k]->value = $v->value;
                   }
                }
             }

			
			
			$catDataTree = array();
			$catDataTree = $catDataTreeRights;
			// End - remove in case there is a memory problem
			
			// Uncomment in case there is a memory problem
			//$catDataTree	= $catData;
		} else {
			$catDataTree	= $catData;
		}	
	
		$categories = array_merge($categories, $catDataTree );

		$category = JHTML::_('select.genericlist',  $categories, 'catid', $onChO, 'value', 'text', $active);

		return $category;
	}
	
	public static function options($type = 0)
	{
		if ($type == 1) {
			$tree[0] 			= new JObject();
			$tree[0]->text 		= JText::_('COM_PHOCADOWNLOAD_MAIN_CSS');
			$tree[0]->value 	= 1;
			$tree[1] 			= new JObject();
			$tree[1]->text 		= JText::_('COM_PHOCADOWNLOAD_CUSTOM_CSS');
			$tree[1]->value 	= 2;
			return $tree;
		}
		
		$db = JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocadownload_categories AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$items = $db->loadObjectList();
	
		$catId	= -1;
		
		$javascript 	= 'class="inputbox" size="1" onchange="submitform( );"';
		
		$tree = array();
		$text = '';
		$tree = PhocaDownloadCategory::CategoryTreeOption($items, $tree, 0, $text, $catId);
		
		return $tree;

	}
	
	public static function getCategoryByFile($id = 0) {
		$db	= JFactory::getDBO();
		$query = 'SELECT c.id, c.title, c.alias'
		. ' FROM #__phocadownload_categories AS c'
		. ' LEFT JOIN #__phocadownload AS a ON a.catid = c.id'
		//. ' WHERE c.published = 1'
		. ' WHERE a.id ='.(int)$id
		. ' ORDER BY c.id'
		. ' LIMIT 1';
		$db->setQuery( $query );
		$item = $db->loadObject();
		return $item;
		
	}
}
?>