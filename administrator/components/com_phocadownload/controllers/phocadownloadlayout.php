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
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class PhocaDownloadCpControllerPhocaDownloadLayout extends JControllerForm
{
	protected	$option 		= 'com_phocadownload';

	function __construct($config=array()) {
		
		parent::__construct($config);
	}
	
	public function execute($task)
	{
		parent::execute($task);
		// Clear the component's cache
		if ($task != 'display') {
			$cache = JFactory::getCache('com_phocadownload');
			$cache->clean();
		}
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
	
	public function back($key = null) {	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";

		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		$recordId = JFactory::getApplication()->input->getInt($key);


		// Attempt to check-in the current record.
		if ($recordId)
		{
			// Check we are holding the id in the edit list.
			if (!$this->checkEditId($context, $recordId))
			{
				// Somehow the person just went to the form - we don't allow that.
				
				$this->setMessage($this->getError(), 'error');
				$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId), 'error');
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option, false));

				return false;
			}

			if ($checkin)
			{
				if ($model->checkin($recordId) === false)
				{
					// Check-in failed, go back to the record and display a notice.
					
					$this->setMessage($this->getError(), 'error');
					$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 'error');
					$this->setRedirect(JRoute::_('index.php?option=' . $this->option, false));

					return false;
				}
			}
		}

		// Clean the session data and redirect.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option, false));

		return true;
	}
}