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

class PhocaDownloadCpControllerPhocaDownloadset extends PhocaDownloadCpController
{
	function __construct() {
		parent::__construct();
		
		$this->registerTask( 'apply'  , 'save' );
	}

	function save() {
		$post					= JFactory::getApplication()->input->get('post');
		$phocaSet				= JFactory::getApplication()->input->get( 'phocaset', array(0), 'post', 'array' );

		$model = $this->getModel( 'phocadownloadset' );
		$errorMsg = '';
		switch ( JFactory::getApplication()->input->getCmd('task') ) {
			case 'apply':
				
				if ($model->store($phocaSet, $errorMsg)) {
					$msg = JText::_( 'Changes to Phoca Download Settings Saved' );
					if ($errorMsg != '') {
						$msg .= '<br />'.JText::_($errorMsg);
					}
				} else {
					$msg = JText::_( 'Error Saving Phoca Download Settings' );
				}
				$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloadset', $msg );
				break;

			case 'save':
			default:
				if ($model->store($phocaSet, $errorMsg)) {
					$msg = JText::_( 'Phoca Download Settings Saved' );
					if ($errorMsg != '') {
						$msg .= '<br />'.JText::_($errorMsg);
					}
				} else {
					$msg = JText::_( 'Error Saving Phoca Download Settings' );
				}
				$this->setRedirect( 'index.php?option=com_phocadownload', $msg );
				break;
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
	}
	
	
	function cancel($key = NULL) {
		$model = $this->getModel( 'phocadownload' );
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_phocadownload' );
	}
}
?>
