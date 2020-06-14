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
 
class PhocaDownloadCpViewPhocaDownloadStat extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $maxandsum;
	protected $t;
	
	function display($tpl = null) {
		
		$this->t			= PhocaDownloadUtils::setVars('stat');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->maxandsum	= $this->get('MaxAndSum');
	
		foreach ($this->items as &$item) {
			if ($item->textonly == 0) {
				$this->ordering[0][] = $item->id;
			}
		}
		
		JHTML::stylesheet( $this->t['s'] );

		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	function addToolbar() {
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['task'].'.php';
		$class	= ucfirst($this->t['task']).'Helper';
		$canDo	= $class::getActions($this->t);
		JToolbarHelper::title( JText::_( $this->t['l'].'_STATISTICS' ), 'chart' );
		JToolbarHelper::custom($this->t['task'].'.back', 'home-2', '', $this->t['l'].'_CONTROL_PANEL', false);
	//	JToolbarHelper::cancel($this->t['task'].'.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> JText::_($this->t['l'] . '_TITLE'),
			'a.filename' 	=> JText::_($this->t['l'] . '_FILENAME'),
			'a.hits' 		=> JText::_($this->t['l'] . '_DOWNLOADS'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>