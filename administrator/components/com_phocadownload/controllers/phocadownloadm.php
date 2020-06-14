<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class PhocaDownloadCpControllerPhocaDownloadM extends JControllerForm
{
	protected	$option 		= 'com_phocadownload';
	protected	$view_list		= 'phocadownloadmanager';
	protected	$layout			= 'edit';

	function __construct() {
		parent::__construct();
		$this->layout = 'edit';
	}

	protected function allowAdd($data = array()) {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_phocadownload');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id') {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.edit', 'com_phocadownload');
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}
	
	function edit($key = NULL, $urlVar = NULL) {
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.'&layout='.$this->layout.'&manager=filemultiple', false));
	}
	
	function cancel($key = NULL) {
		$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloadfiles' );
	}
}
?>
