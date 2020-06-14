<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die();
jimport('joomla.application.component.modeladmin');


class PhocaDownloadCpModelPhocaDownloadLic extends JModelAdmin
{
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	public 		$typeAlias 		= 'com_phocadownload.phocadownloadlic';
	
	protected function canDelete($record)
	{
		//$user = JFactory::getUser();
		return parent::canDelete($record);
	}
	
	protected function canEditState($record)
	{
		//$user = JFactory::getUser();
		return parent::canEditState($record);
	}
	
	public function getTable($type = 'PhocaDownloadLic', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloadlic', 'phocadownloadlic', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownload.edit.phocadownloadlic.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplicationHelper::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_licenses');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toSql();
			//$table->modified_by	= $user->get('id');
		}
	}
}
?>