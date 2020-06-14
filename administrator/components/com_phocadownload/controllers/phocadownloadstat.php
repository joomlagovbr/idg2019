<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

class PhocaDownloadCpControllerPhocaDownloadstat extends JControllerAdmin
{
	public function __construct() {
		parent::__construct();
	}
/*
	public function cancel($key = NULL) {
		$model = $this->getModel( 'phocadownload' );
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_phocadownload' );
	}*/
	
	public function back($key = null) {	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$this->setRedirect(JRoute::_('index.php?option=com_phocadownload', false));

		return true;
	}
	
	public function saveOrderAjax() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		\Joomla\Utilities\ArrayHelper::toInteger($pks);
		\Joomla\Utilities\ArrayHelper::toInteger($order);
		$model = $this->getModel();
		$return = $model->saveorder($pks, $order);
		if ($return) { echo "1";}
		JFactory::getApplication()->close();
	}
}
?>
