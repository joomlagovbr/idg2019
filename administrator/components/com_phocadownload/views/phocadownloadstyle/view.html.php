<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


class PhocaDownloadCpViewPhocaDownloadStyle extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $t;

	public function display($tpl = null)
	{
		$this->t		= PhocaDownloadUtils::setVars('style');
		JHTML::stylesheet( $this->t['s'] );
		
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->ftp		= JClientHelper::setCredentialsFromRequest('ftp');
		$model 			= $this->getModel();
		
		// Set CSS for codemirror
		JFactory::getApplication()->setUserState('editor.source.syntax', 'css');
		
		
		// New or edit
		if (!$this->form->getValue('id') || $this->form->getValue('id') == 0) {
			$this->form->setValue('source', null, '');
			$this->form->setValue('type', null, 2);
			$this->t['ssuffixtype'] = JText::_($this->t['l'].'_WILL_BE_CREATED_FROM_TITLE');
		
		} else {
			$this->source	= $model->getSource($this->form->getValue('id'), $this->form->getValue('filename'), $this->form->getValue('type'));
			$this->form->setValue('source', null, $this->source->source);
			$this->t['ssuffixtype'] = '';
		}
		
		// Only help input form field - to display Main instead of 1 and Custom instead of 2
		if ($this->form->getValue('type') == 1) { 
			$this->form->setValue('typeoutput', null, JText::_($this->t['l'].'_MAIN_CSS'));
		} else {
			$this->form->setValue('typeoutput', null, JText::_($this->t['l'].'_CUSTOM_CSS'));
		}

		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$bar 		= JToolbar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$class		= ucfirst($this->t['tasks']).'Helper';
		$canDo		= $class::getActions($this->t, $this->state->get('filter.category_id'));

		$text = $isNew ? JText::_( $this->t['l'] . '_NEW' ) : JText::_($this->t['l'] . '_EDIT');
		JToolbarHelper::title(   JText::_( $this->t['l'] . '_STYLE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'eye');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolbarHelper::apply($this->t['task'].'.apply', 'JTOOLBAR_APPLY');
			JToolbarHelper::save($this->t['task'].'.save', 'JTOOLBAR_SAVE');
		}

		JToolbarHelper::cancel($this->t['task'].'.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

}
?>
