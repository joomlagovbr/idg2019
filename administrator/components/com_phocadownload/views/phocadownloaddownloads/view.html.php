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

class PhocaDownloadCpViewPhocaDownloadDownloads extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $maxandsum;
	protected $t;

	function display($tpl = null) {

		$this->t			= PhocaDownloadUtils::setVars('download');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->maxandsum	= $this->get('MaxAndSum');

		JHTML::stylesheet( $this->t['s'] );

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
		//$state	= $this->get('State');
		$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= $class::getActions($this->t);

		JToolbarHelper::title( JText::_( $this->t['l'].'_DOWNLOADS' ), 'download' );

		if ($canDo->get('core.edit')){

			$bar = JToolbar::getInstance('toolbar');

			$dhtml = '<button class="btn btn-small" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert(\''.JText::_('COM_PHOCADOWNLOAD_SELECT_ITEM_RESET').'\');}else{if(confirm(\''.JText::_('COM_PHOCADOWNLOAD_WARNING_RESET_DOWNLOADS').'\')){submitbutton(\''.$this->t['tasks'].'.reset\');}}" ><i class="icon-reset" title="'.JText::_('COM_PHOCADOWNLOAD_RESET').'"></i> '.JText::_('COM_PHOCADOWNLOAD_RESET').'</button>';
			$bar->appendButton('Custom', $dhtml);
			JToolbarHelper::divider();
			//JToolbarHelper::custom('phocadownloaduserstat.reset', 'reset.png', '', 'COM_PHOCADOWNLOAD_RESET' , false);

            if ($canDo->get('core.delete')) {
			    JToolbarHelper::deleteList( JText::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['tasks'].'.delete', $this->t['l'].'_DELETE');
		    }
		}

		//JToolbarHelper::cancel($this->t['tasks'].'.cancel', 'JTOOLBAR_CLOSE');

		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(


			'usernameno'	=> JText::_($this->t['l'] . '_USER'),
			'username'		=> JText::_($this->t['l'] . '_USERNAME'),
			'a.count'	 	=> JText::_($this->t['l'] . '_COUNT'),
			'filename'		=> JText::_($this->t['l'] . '_FILENAME')

		);
	}

}
?>
