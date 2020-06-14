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
 
class PhocaDownloadCpViewPhocaDownloadLogs extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $maxandsum;
	protected $t;
	
	function display($tpl = null) {
		
		$this->t			= PhocaDownloadUtils::setVars('log');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->t['p']       = JComponentHelper::getParams('com_phocadownload');
		
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

		JToolbarHelper::title( JText::_( $this->t['l'].'_LOGGING' ), 'file-2' );

		if ($canDo->get('core.edit')){
			
			$bar = JToolbar::getInstance('toolbar');

			$dhtml = '<button class="btn btn-small" onclick="javascript:if(confirm(\''.addslashes(JText::_('COM_PHOCADOWNLOAD_WARNING_RESET_LOG')).'\')){submitbutton(\'phocadownloadlogs.reset\');}" ><i class="icon-approve" title="'.JText::_('COM_PHOCADOWNLOAD_RESET_LOG').'"></i> '.JText::_('COM_PHOCADOWNLOAD_RESET_LOG').'</button>';
			$bar->appendButton('Custom', $dhtml);
			JToolbarHelper::divider();
			//JToolbarHelper::custom('phocadownloaduserstat.reset', 'reset.png', '', 'COM_PHOCADOWNLOAD_RESET' , false);
		}
	
		//JToolbarHelper::cancel($this->t['tasks'].'.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
	
	protected function getSortFields() {
		return array(
			'a.date'	 	=> JText::_($this->t['l'] . '_DATE'),
			'usernameno'	=> JText::_($this->t['l'] . '_USER'),
			'username'		=> JText::_($this->t['l'] . '_USERNAME'),
			'd.title'		=> JText::_($this->t['l'] . '_TITLE'),
			'filename'		=> JText::_($this->t['l'] . '_FILENAME'),
			'category_id'	=> JText::_($this->t['l'] . '_CATEGORY'),
			'a.ip'	 		=> JText::_($this->t['l'] . '_IP'),
			'a.page'	 	=> JText::_($this->t['l'] . '_PAGE'),
			'a.type'	 	=> JText::_($this->t['l'] . '_TYPE'),
			'a.id'	 		=> JText::_($this->t['l'] . '_ID')
			
		);
	}
	
}
?>