<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );
 
class PhocaDownloadCpViewPhocaDownloadFiles extends JViewLegacy
{

	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	
	function display($tpl = null) {
		
		$this->t			= PhocaDownloadUtils::setVars('file');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->catid][] = $item->id;
		}
		
		JHTML::stylesheet( $this->t['s'] );
		$this->tmpl['notapproved'] 	= $this->get( 'NotApprovedFile' );
	
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		$state	= $this->get('State');
		$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= $class::getActions($this->t, $state->get('filter.file_id'));
		$user  = JFactory::getUser();
		$bar = JToolbar::getInstance('toolbar');
		
		JToolbarHelper::title( JText::_($this->t['l'].'_FILES'), 'file.png' );
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew( $this->t['task'].'.add','JTOOLBAR_NEW');
			JToolbarHelper::addNew( $this->t['task'].'.addtext', $this->t['l'].'_ADD_TEXT');
			JToolbarHelper::custom( $this->t['c'].'m.edit', 'multiple.png', '', $this->t['l'].'_MULTIPLE_ADD' , false);
		}
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList($this->t['task'].'.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.create')) {
			//JToolbarHelper::divider();
			//JToolbarHelper::custom( $this->t['task'].'.copyquick','copy.png', '', $this->t['l'].'_QUICK_COPY', true);
			//JToolbarHelper::custom( $this->t['task'].'.copy','copy.png', '', $this->t['l'].'_COPY', true);
		}
		
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::divider();
			JToolbarHelper::custom($this->t['tasks'].'.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolbarHelper::custom($this->t['tasks'].'.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::custom( $this->t['tasks'].'.approve', 'approve.png', '',  $this->t['l'].'_APPROVE' , true);
			JToolbarHelper::custom( $this->t['tasks'].'.disapprove', 'disapprove.png', '',  $this->t['l'].'_NOT_APPROVE' , true);
		}

		if ($canDo->get('core.delete')) {
			JToolbarHelper::deleteList( JText::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['tasks'].'.delete', $this->t['l'].'_DELETE');
		}
		
		// Add a batch button
		if ($user->authorise('core.edit'))
		{
			JHtml::_('bootstrap.renderModal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> JText::_($this->t['l'] . '_TITLE'),
			'a.filename' 	=> JText::_($this->t['l'] . '_FILENAME'),
			'a.date' 		=> JText::_($this->t['l'] . '_DATE'),
			'a.hits' 		=> JText::_($this->t['l'] . '_DOWNLOADS'),
			'a.owner_id'	=> JText::_($this->t['l'] . '_OWNER'),
			'uploadusername'=> JText::_($this->t['l'] . '_UPLOADED_BY'),
			'a.published' 	=> JText::_($this->t['l'] . '_PUBLISHED'),
			'a.approved' 	=> JText::_($this->t['l'] . '_APPROVED'),
			'category_id' 	=> JText::_($this->t['l'] . '_CATEGORY'),
			'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>