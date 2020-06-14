<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport('joomla.application.component.modeladmin');
use Joomla\String\StringHelper;

class PhocaDownloadCpModelPhocaDownloadCat extends JModelAdmin
{
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	public 		$typeAlias 		= 'com_phocadownload.phocadownloadcat';

	protected function canDelete($record) {
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_phocadownload.phocadownloadcat.'.(int) $record->catid);
		} else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record){
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_phocadownload.phocadownloadcat.'.(int) $record->catid);
		} else {
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'PhocaDownloadCat', $prefix = 'Table', $config = array()){
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {

		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloadcat', 'phocadownloadcat', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownload.edit.phocadownloadcat.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	public function getItem($pk = null) {
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			if (isset($item->metadata)) {
				$registry = new JRegistry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
			}
		}

		return $item;
	}

	protected function prepareTable($table){
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplicationHelper::stringURLSafe($table->alias);

		$table->parent_id	= PhocaDownloadUtils::getIntFromString($table->parent_id);

		if (empty($table->alias)) {
			$table->alias = JApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_categories');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		} else {
			// Set the values
			//$table->modified	= $date->toSql();
			//$table->modified_by	= $user->get('id');
		}
	}


	protected function getReorderConditions($table = null)
	{
		$condition = array();
		$condition[] = 'parent_id = '. (int) $table->parent_id;
		//$condition[] = 'state >= 0';
		return $condition;
	}






	/*
	 * Custom Save method - libraries/joomla/application/component/modeladmin.php
	 */
	public function save($data)
	{

		// = = = = = = = = = =
		// Default VALUES FOR Rights in FRONTEND
		// ACCESS -  0: all users can see the category (registered or not registered)
		//             if registered or not registered it will be set in ACCESS LEVEL not here)
		//			   if -1 - user was not selected so every registered or special users can see category
		// UPLOAD - -2: nobody can upload or add images in front (if 0 - every users can do it)
		// DELETE - -2: nobody can upload or add images in front (if 0 - every users can do it)
		if(!isset($data['accessuserid'])) { $data['accessuserid'] = array();}
		if(!isset($data['uploaduserid'])) { $data['uploaduserid'] = array();}
		if(!isset($data['deleteuserid'])) { $data['deleteuserid'] = array();}
		$accessUserIdArray	= PhocaDownloadUtils::toArray($data['accessuserid']);
		$uploadUserIdArray	= PhocaDownloadUtils::toArray($data['uploaduserid']);
		$deleteUserIdArray	= PhocaDownloadUtils::toArray($data['deleteuserid']);

		if (isset($data['access']) && (int)$data['access'] > 0 && (int)$accessUserIdArray[0] == 0) {
			$accessUserId[0]	= -1;
		}
		$data['accessuserid'] = implode(',',$accessUserIdArray);
		$data['uploaduserid'] = implode(',',$uploadUserIdArray);
		$data['deleteuserid'] = implode(',',$deleteUserIdArray);

		// = = = = = = = = = =


		// Initialise variables;
		//$dispatcher = JEventDispatcher::getInstance();
		$table		= $this->getTable();
		$pk			= (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Load the row if saving an existing record.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			throw new Exception($table->getError(), 500);
			return false;
		}

		if(intval($table->date) == 0) {
			$table->date = JFactory::getDate()->toSql();
		}

		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check()) {
			throw new Exception($table->getError(), 500);
			return false;
		}

		// Trigger the onContentBeforeSave event.
		/*$result = $dispatcher->trigger($this->event_before_save, array($this->option.'.'.$this->name, $table, $isNew));
		if (in_array(false, $result, true)) {
			throw new Exception($table->getError(), 500);
			return false;
		}*/

		// Store the data.
		if (!$table->store()) {
			throw new Exception($table->getError(), 500);
			return false;
		}



		// Clean the cache.
		$cache = JFactory::getCache($this->option);
		$cache->clean();

		// Trigger the onContentAfterSave event.
		//$dispatcher->trigger($this->event_after_save, array($this->option.'.'.$this->name, $table, $isNew));

		$pkName = $table->getKeyName();
		if (isset($table->$pkName)) {
			$this->setState($this->getName().'.id', $table->$pkName);
		}
		$this->setState($this->getName().'.new', $isNew);



		return true;
	}






	function delete(&$cid = array()) {
		$app	= JFactory::getApplication();
		$db 	= JFactory::getDBO();

		$result = false;
		if (count( $cid )) {
			\Joomla\Utilities\ArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			// FIRST - if there are subcategories - - - - -
			$query = 'SELECT c.id, c.name, c.title, COUNT( s.parent_id ) AS numcat'
			. ' FROM #__phocadownload_categories AS c'
			. ' LEFT JOIN #__phocadownload_categories AS s ON s.parent_id = c.id'
			. ' WHERE c.id IN ( '.$cids.' )'
			. ' GROUP BY c.id'
			;
			$db->setQuery( $query );

			if (!($rows2 = $db->loadObjectList())) {
				throw new Exception($db->stderr('Load Data Problem'), 500);
				return false;
			}

			// Add new CID without categories which have subcategories (we don't delete categories with subcat)
			$err_cat = array();
			$cid 	 = array();
			foreach ($rows2 as $row) {
				if ($row->numcat == 0) {
					$cid[] = (int) $row->id;
				} else {
					$err_cat[] = $row->title;
				}
			}
			// - - - - - - - - - - - - - - -

			// Images with new cid - - - - -
			if (count( $cid )) {
				\Joomla\Utilities\ArrayHelper::toInteger($cid);
				$cids = implode( ',', $cid );

				// Select id's from phocadownload tables. If the category has some images, don't delete it
				$query = 'SELECT c.id, c.name, c.title, COUNT( s.catid ) AS numcat'
				. ' FROM #__phocadownload_categories AS c'
				. ' LEFT JOIN #__phocadownload AS s ON s.catid = c.id'
				. ' WHERE c.id IN ( '.$cids.' )'
				. ' GROUP BY c.id';

				$db->setQuery( $query );

				if (!($rows = $db->loadObjectList())) {

					throw new Exception($db->stderr('Load Data Problem'), 500);
					return false;
				}

				$err_img = array();
				$cid 	 = array();
				foreach ($rows as $row) {
					if ($row->numcat == 0) {
						$cid[] = (int) $row->id;
					} else {
						$err_img[] = $row->title;
					}
				}

				if (count( $cid )) {
					$cids = implode( ',', $cid );
					$query = 'DELETE FROM #__phocadownload_categories'
					. ' WHERE id IN ( '.$cids.' )';
					$db->setQuery( $query );
					if (!$db->execute()) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}

					// Delete items in phocadownload_user_category
				/*	$query = 'DELETE FROM #__phocadownload_user_category'
					. ' WHERE catid IN ( '.$cids.' )';
					$db->setQuery( $query );
					if (!$db->query()) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}*/
				}
			}

			// There are some images in the category - don't delete it
			$msg = '';
			if (count( $err_cat ) || count( $err_img )) {
				if (count( $err_cat )) {
					$cids_cat = implode( ", ", $err_cat );
					$msg .= JText::plural( 'COM_PHOCADOWNLOAD_ERROR_DELETE_CONTAIN_CAT', $cids_cat );
				}

				if (count( $err_img )) {
					$cids_img = implode( ", ", $err_img );
					$msg .= JText::plural( 'COM_PHOCADOWNLOAD_ERROR_DELETE_CONTAIN_FILE', $cids_img );
				}
				$link = 'index.php?option=com_phocadownload&view=phocadownloadcats';
				$app->redirect($link, $msg);
			}
		}
		return true;
	}

	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId	= (int) $value;

		$app = JFactory::getApplication();
		$table	= $this->getTable();
		$db		= $this->getDbo();

		// Check that the category exists
		if ($categoryId) {
			$categoryTable = JTable::getInstance('PhocaDownloadCat', 'Table');

			if (!$categoryTable->load($categoryId)) {
				if ($error = $categoryTable->getError()) {
					// Fatal error

					throw new Exception($error, 500);
					return false;
				}
				else {

					throw new Exception(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'), 500);
					return false;
				}
			}
		}

		//if (empty($categoryId)) {
		if (!isset($categoryId)) {

			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'), 500);
			return false;
		}

		// Check that the user has create permission for the component
		$extension	= JFactory::getApplication()->input->getCmd('option');
		$user		= JFactory::getUser();
		if (!$user->authorise('core.create', $extension)) {

			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'), 500);
			return false;
		}

		//$i		= 0;

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$table->reset();

			// Check that the row actually exists
			if (!$table->load($pk)) {
				if ($error = $table->getError()) {
					// Fatal error
					throw new Exception($error, 500);
					return false;
				}
				else {
					// Not fatal error
					$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk), 'error');
					continue;
				}
			}

			// Alter the title & alias
			$data = $this->generateNewTitle($categoryId, $table->alias, $table->title);
			$table->title   = $data['0'];
			$table->alias   = $data['1'];

			// Reset the ID because we are making a copy
			$table->id		= 0;

			// New category ID
			$table->parent_id	= $categoryId;

			// Ordering
			$table->ordering = $this->increaseOrdering($categoryId);

			$table->hits = 0;

			// Check the row.
			if (!$table->check()) {
				throw new Exception($table->getError(), 500);
				return false;
			}

			// Store the row.
			if (!$table->store()) {
				throw new Exception($table->getError(), 500);
				return false;
			}

			// Get the new item ID
			$newId = $table->get('id');

			// Add the new ID to the array
			$newIds[$pk]	= $newId;
			//$i++;
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	protected function batchMove($value, $pks, $contexts)
	{
		$categoryId	= (int) $value;
		$app = JFactory::getApplication();
		$table	= $this->getTable();
		//$db		= $this->getDbo();

		// Check that the category exists
		if ($categoryId) {
			$categoryTable = JTable::getInstance('PhocaDownloadCat', 'Table');
			if (!$categoryTable->load($categoryId)) {
				if ($error = $categoryTable->getError()) {
					// Fatal error
					throw new Exception($error, 500);
					return false;
				}
				else {

					throw new Exception(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'), 500);
					return false;
				}
			}
		}

		//if (empty($categoryId)) {
		if (!isset($categoryId)) {
			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'), 500);
			return false;
		}

		// Check that user has create and edit permission for the component
		$extension	= JFactory::getApplication()->input->getCmd('option');
		$user		= JFactory::getUser();
		if (!$user->authorise('core.create', $extension)) {

			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'), 500);
			return false;
		}

		if (!$user->authorise('core.edit', $extension)) {

			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'), 500);
			return false;
		}

		// Parent exists so we let's proceed
		foreach ($pks as $pk)
		{
			// Check that the row actually exists
			if (!$table->load($pk)) {
				if ($error = $table->getError()) {
					// Fatal error
					throw new Exception($error, 500);
					return false;
				}
				else {
					// Not fatal error

					$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk), 'error');
					continue;
				}
			}

			// Set the new category ID
			$table->parent_id = $categoryId;


			// Cannot move the node to be a child of itself.
			if ((int)$table->id == (int)$categoryId) {

				throw new Exception(JText::sprintf('JLIB_DATABASE_ERROR_INVALID_NODE_RECURSION', get_class($pk)), 500);
				return false;
			}

			// Check the row.
			if (!$table->check()) {
				throw new Exception($table->getError(), 500);
				return false;
			}

			// Store the row.
			if (!$table->store()) {
				throw new Exception($table->getError(), 500);
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}


	public function increaseOrdering($categoryId) {

		$ordering = 1;
		$this->_db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_categories WHERE parent_id='.(int)$categoryId);
		$max = $this->_db->loadResult();
		$ordering = $max + 1;
		return $ordering;
	}


	public function batch($commands, $pks, $contexts)
	{

		// Sanitize user ids.
		$pks = array_unique($pks);
		\Joomla\Utilities\ArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks)) {
			throw new Exception(JText::_('JGLOBAL_NO_ITEM_SELECTED'), 500);
			return false;
		}

		$done = false;

		if (!empty($commands['assetgroup_id'])) {
			if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		//PHOCAEDIT - Parent is by Phoca 0 not 1 like by Joomla!
		$comCat =false;
		if ($commands['category_id'] == '') {
			$comCat = false;
		} else if ( $commands['category_id'] == '0') {
			$comCat = true;
		} else if ((int)$commands['category_id'] > 0) {
			$comCat = true;
		}

		if ($comCat)
		//if (isset($commands['category_id']))
		{
			$cmd = \Joomla\Utilities\ArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands['category_id'], $pks, $contexts);
				if (is_array($result))
				{
					$pks = $result;
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands['category_id'], $pks, $contexts))
			{
				return false;
			}
			$done = true;
		}

		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done) {

			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'), 500);
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}


	protected function generateNewTitle($category_id, $alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();
		while ($table->load(array('alias' => $alias, 'parent_id' => $category_id)))
		{
			$title = StringHelper::increment($title);
			$alias = StringHelper::increment($alias, 'dash');
		}

		return array($title, $alias);
	}

}
?>
