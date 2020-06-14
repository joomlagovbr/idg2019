<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die();
jimport('joomla.application.component.view');
class phocaDownloadViewphocaDownloadLinkCat extends JViewLegacy
{

	protected $t;

	function display($tpl = null) {
		$app	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');
		JHtml::_('behavior.keepalive');
		JHtml::_('formbehavior.chosen', 'select');
		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = JURI::base();

		}

		$document	= JFactory::getDocument();
		$uri		= \Joomla\CMS\Uri\Uri::getInstance();
		JHTML::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );

		$eName				= $app->input->get('e_name');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinks&amp;tmpl=component&amp;e_name='.$this->t['ename'];

		$model 			= $this->getModel();

		// build list of categories
		//$javascript 	= 'class="inputbox" size="1" onchange="submitform( );"';
		$javascript 	= 'class="inputbox" size="1"';
		$filter_catid	= '';

		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocadownload_categories AS a'
		. ' WHERE a.published = 1'
		//. ' AND a.approved = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$phocadownloads = $db->loadObjectList();

		$tree = array();
		$text = '';
		$tree = PhocaDownloadCategory::CategoryTreeOption($phocadownloads, $tree, 0, $text, -1);
		array_unshift($tree, JHTML::_('select.option', '0', '- '.JText::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -', 'value', 'text'));
		$lists['catid'] = JHTML::_( 'select.genericlist', $tree, 'catid',  $javascript , 'value', 'text', $filter_catid );
		//-----------------------------------------------------------------------

		//$this->assignRef('lists',	$lists);
        $this->t['lists'] = $lists;
		parent::display($tpl);
	}
}
?>
