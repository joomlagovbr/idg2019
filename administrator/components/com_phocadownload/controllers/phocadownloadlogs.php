<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class PhocaDownloadCpControllerPhocaDownloadLogs extends PhocaDownloadCpController
{
	function __construct() {
		parent::__construct();
		$this->registerTask( 'reset', 'reset');		
	}
	
	function reset() {

		$model = $this->getModel( 'phocadownloadlog' );

		if ($model->reset($cid)) {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_SUCCESS_RESET_LOG_STAT' );
		} else {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_ERROR_RESET_LOG_STAT' );
		}
		
		$link = 'index.php?option=com_phocadownload&view=phocadownloadlogs';
		$this->setRedirect($link, $msg);
	}
}
?>
