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
use Joomla\String\StringHelper;

class PhocaDownloadCpViewPhocaDownloadLinkFile extends JViewLegacy
{
	public $_context 	= 'com_phocadownload.phocadownloadlinkfile';
	protected $t;

	function display($tpl = null) {
		$app = JFactory::getApplication();
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');
		JHtml::_('behavior.keepalive');
		JHtml::_('formbehavior.chosen', 'select');

		$uri		= \Joomla\CMS\Uri\Uri::getInstance();
		$document	= JFactory::getDocument();
		$db		    = JFactory::getDBO();

		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = JURI::base();

		}

		JHTML::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );

		$eName				= JFactory::getApplication()->input->get('e_name');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['type']		= JFactory::getApplication()->input->get( 'type', 1, '', 'int' );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinks&amp;tmpl=component&amp;e_name='.$this->t['ename'];


		$params = JComponentHelper::getParams('com_phocadownload') ;

		//Filter
		$context			= 'com_phocadownload.phocadownload.list.';
		//$sectionid			= JFactory::getApplication()->input->get( 'sectionid', -1, '', 'int' );
		//$redirect			= $sectionid;
		$option				= JFactory::getApplication()->input->getCmd( 'option' );

		$filter_state		= $app->getUserStateFromRequest( $this->_context.'.filter_state',	'filter_state', '',	'word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context.'.filter_catid',	'filter_catid', 0,	'int' );
		$catid				= $app->getUserStateFromRequest( $this->_context.'.catid',	'catid', 0,	'int');
	//	$filter_sectionid	= $app->getUserStateFromRequest( $this->_context.'.filter_sectionid','filter_sectionid',	-1,	'int');
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',		'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$search				= $app->getUserStateFromRequest( $this->_context.'.search','search', '', 'string' );
		//$search				= J String::strtolower( $search );
		$search				= StringHelper::strtolower( $search );

		// Get data from the model
		$items		= $this->get( 'Data');
		$total		=  $this->get( 'Total');
		$pagination =  $this->get( 'Pagination' );

		// build list of categories

		if ($this->t['type'] != 4) {
			$javascript = 'class="inputbox" size="1" onchange="submitform( );"';
		} else {
			$javascript	= '';
		}
		// get list of categories for dropdown filter
		$filter = '';

		//if ($filter_sectionid > 0) {
		//	$filter = ' WHERE cc.section = '.$db->Quote($filter_sectionid);
		//}

		// get list of categories for dropdown filter
		$query = 'SELECT cc.id AS value, cc.title AS text' .
				' FROM #__phocadownload_categories AS cc' .
				$filter .
				' ORDER BY cc.ordering';

		if ($this->t['type'] != 4) {
             $lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, true, true);
        } else {
             $lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, false, true);
        }
		/*
		if ($this->t['type'] != 4) {
			$lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, true);
		} else {
			$lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, false);
		}*/

		// sectionid
		/*$query = 'SELECT s.title AS text, s.id AS value'
		. ' FROM #__phocadownload_sections AS s'
		. ' WHERE s.published = 1'
		. ' ORDER BY s.ordering';

		$lists['sectionid'] = PhocaDownloadCategory::filterSection($query, $filter_sectionid);*/

		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;

		// search filter
		$lists['search']= $search;


		$user = JFactory::getUser();
		$uriS = $uri->toString();
		//$this->assignRef('user',		$user);
		//$this->assignRef('lists',		$lists);
        $this->t['lists'] = $lists;


		//$this->assignRef('items',		$items);
        $this->t['items'] = $items;
		//$this->assignRef('pagination',	$pagination);
        $this->t['pagination'] = $pagination;
		//$this->assignRef('request_url',	$uriS);
        $this->t['request_url'] = $uriS;

		parent::display($tpl);
	}
}
?>
