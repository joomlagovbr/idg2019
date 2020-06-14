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

class PhocaDownloadCpViewPhocaDownloadLayout extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	protected $t;

	public function display($tpl = null) {
		
		$this->t		= PhocaDownloadUtils::setVars('layout');
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		
		JHTML::stylesheet( $this->t['s'] );

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$bar 		= JToolbar::getInstance('toolbar');
		$user		= JFactory::getUser();
		//$isNew		= ($this->item->id == 0);
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$class		= ucfirst($this->t['tasks']).'Helper';
		$canDo		= $class::getActions($this->t);
		
		JToolbarHelper::title(   JText::_( $this->t['l'].'_LAYOUT' ), 'file-2' );
		JToolbarHelper::custom($this->t['task'].'.back', 'home-2', '', $this->t['l'].'_CONTROL_PANEL', false);
		//JToolbarHelper::cancel('phocadownloadlayout.cancel', 'JTOOLBAR_CANCEL');
		
		if ($canDo->get('core.edit')) {
			JToolbarHelper::apply($this->t['task'].'.apply', 'JTOOLBAR_APPLY');
			//JToolbarHelper::save('phocapdfplugin.save', 'JTOOLBAR_SAVE');
		}
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>