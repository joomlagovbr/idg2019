<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
 
class PhocaDownloadCpViewPhocaDownloadStyles extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;


	function display($tpl = null) {
		
		$this->t			= PhocaDownloadUtils::setVars('style');
		$model				= $this->getModel();
		$model->checkItems();
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		JHTML::stylesheet( $this->t['s'] );
		
		foreach ($this->items as &$item) {
			$this->ordering[$item->type][] = $item->id;
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		$state	= $this->get('State');
		$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= $class::getActions($this->t, $state->get('filter.category_id'));
		
		JToolbarHelper::title( JText::_( $this->t['l'].'_STYLES' ), 'eye' );
		
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew( $this->t['task'].'.add','JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList($this->t['task'].'.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::divider();
			JToolbarHelper::custom($this->t['tasks'].'.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolbarHelper::custom($this->t['tasks'].'.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
	
		if ($canDo->get('core.delete')) {
			JToolbarHelper::deleteList(  JText::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['tasks'].'.delete',  $this->t['l'].'_DELETE');
		}
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title'	 	=> JText::_($this->t['l'] . '_TITLE'),
			'a.filename'	=> JText::_($this->t['l'] . '_FILENAME'),
			'a.published'	=> JText::_($this->t['l'] . '_PUBLISHED'),
			'a.type'	 	=> JText::_($this->t['l'] . '_TYPE'),
			'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>