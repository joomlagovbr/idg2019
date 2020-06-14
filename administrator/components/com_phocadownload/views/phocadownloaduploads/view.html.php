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

jimport( 'joomla.filesystem.file' ); 
class PhocaDownloadCpViewPhocaDownloadUploads extends JViewLegacy
{

	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	
	
	function display($tpl = null) {
		
		$this->t			= PhocaDownloadUtils::setVars('upload');
		$this->items			= $this->get('Items');
		$this->pagination		= $this->get('Pagination');
		$this->state			= $this->get('State');

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

		JToolbarHelper::title( JText::_( $this->t['l'].'_UPLOADS' ), 'upload' );

		if ($canDo->get('core.admin')) {
			
			$bar = JToolbar::getInstance('toolbar');

			$dhtml = '<button class="btn btn-small" onclick="javascript:if(confirm(\''.addslashes(JText::_('COM_PHOCADOWNLOAD_WARNING_AUTHORIZE_ALL')).'\')){submitbutton(\'phocadownloaduploads.approveall\');}" ><i class="icon-approve" title="'.JText::_('COM_PHOCADOWNLOAD_APPROVE_ALL').'"></i> '.JText::_('COM_PHOCADOWNLOAD_APPROVE_ALL').'</button>';
			$bar->appendButton('Custom', $dhtml);
		

			JToolbarHelper::divider();
		}
	
		
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
	
	protected function getSortFields() {
		return array(
			
			'd.title' 		=> JText::_($this->t['l'] . '_TITLE'),
			'd.filename' 	=> JText::_($this->t['l'] . '_FILENAME'),
			'usernameno'	=> JText::_($this->t['l'] . '_USER'),
			'username'		=> JText::_($this->t['l'] . '_USERNAME'),
			'a.count'	 	=> JText::_($this->t['l'] . '_COUNT')
			
		);
	}
}
?>