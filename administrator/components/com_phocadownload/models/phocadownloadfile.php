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
defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');

class PhocaDownloadCpModelPhocaDownloadFile extends JModelAdmin
{
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	public 		$typeAlias 		= 'com_phocadownload.phocadownloadfile';

	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_phocadownload.phocadownloadfile.'.(int) $record->catid);
		} else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_phocadownload.phocadownloadfile.'.(int) $record->catid);
		} else {
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'PhocaDownload', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {

		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloadfile', 'phocadownloadfile', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownload.edit.phocadownload.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

		public function getItem($pk = null)
	{
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

	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplicationHelper::stringURLSafe($table->alias);


		$table->confirm_license	= PhocaDownloadUtils::getIntFromString($table->confirm_license);
		$table->hits			= PhocaDownloadUtils::getIntFromString($table->hits);
		$table->tokenhits		= PhocaDownloadUtils::getIntFromString($table->tokenhits);

		if (empty($table->alias)) {
			$table->alias = JApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				//$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload');
				$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload WHERE catid = '.(int)$table->catid);
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



	protected function getReorderConditions($table = null)
	{
		$condition = array();
		$condition[] = 'catid = '. (int) $table->catid;
		//$condition[] = 'state >= 0';
		return $condition;
	}

	function approve(&$pks, $value = 1)
	{
		// Initialise variables.
		//$dispatcher	= JEventDispatcher::getInstance();
		$user		= JFactory::getUser();
		$table		= $this->getTable('phocadownload');
		$pks		= (array) $pks;

		// Include the content plugins for the change of state event.
		JPluginHelper::importPlugin('content');

		// Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$this->canEditState($table)) {
					// Prune items that you can't change.
					unset($pks[$i]);

					throw new Exception(JText::_('JLIB_APPLICATION_ERROR_EDIT_STATE_NOT_PERMITTED'), 403);
					return false;
				}
			}
		}

		// Attempt to change the state of the records.
		if (!$table->approve($pks, $value, $user->get('id'))) {
			throw new Exception($table->getError(), 500);
			return false;
		}

		$context = $this->option.'.'.$this->name;

		// Trigger the onContentChangeState event.
		/* $result = $dispatcher->trigger($this->event_change_state, array($context, $pks, $value));
		if (in_array(false, $result, true)) {
			throw new Exception($table->getError(), 500);
			return false;
		} */

		return true;
	}

	function save($data) {

		//$data['filesize'] 	= PhocaDownloadUtils::getFileSize($data['filename'], 0);

		if ($data['alias'] == '') {
			$data['alias'] = $data['title'];
		}

		//$data['alias'] = PhocaDownloadText::get AliasName($data['alias']);


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

		// =================================================
		// Make a copy for play and preview
		$paramsC 	= JComponentHelper::getParams('com_phocadownload') ;
		$papCopy 	= $paramsC->get( 'pap_copy', 0 );
		$overwriteExistingFiles = $paramsC->get( 'overwrite_existing_files', 0 );
		$path		= PhocaDownloadPath::getPathSet();


		if ($papCopy == 2 || $papCopy == 3) {
			$canPlay			= PhocaDownloadFile::canPlay($data['filename']);
			$canPreview 		= PhocaDownloadFile::canPreview($data['filename']);
			$filepath			= JPath::clean($path['orig_abs_ds'] . '/'.$data['filename']);
			$filepathPAP 		= JPath::clean($path['orig_abs_pap_ds'] . '/'.$data['filename']);
			$filepathPAPFolder	= JPath::clean($path['orig_abs_pap_ds'] . '/'. PhocaDownloadFile::getFolderFromTheFile($data['filename']));

			if ($canPlay || $canPreview) {

				$uploadPAP = 1;// upload file for preview and play
				if (JFile::exists($filepathPAP) && $overwriteExistingFiles == 0) {
					//$errUploadMsg = JText::_("COM_PHOCADOWNLOAD_FILE_ALREADY_EXISTS");
					//return false;
					$uploadPAP = 0; // don't upload if it exists, it is not main file, don't do false and exit

					if ($canPlay == 1) {
						// set new file only no other is set
						if ($data['filename_play'] != '') {
							$uploadPAP = 0;
						} else {
							$data['filename_play']		=  $data['filename'];
						}
					} else if ($canPreview == 1) {
						// set new file only no other is set
						if ($data['filename_preview'] != '') {
							$uploadPAP = 0;
						} else {
							$data['filename_preview']	=  $data['filename'];
						}
					}
				}


				// Overwrite file and add no new item to database
				$fileExistsPAP = 0;
				if (JFile::exists($filepathPAP) && $overwriteExistingFiles == 1) {
					$fileExistsPAP = 1;

					if ($canPlay == 1) {
						// set new file only no other is set or it is the same like currect - to overwrite updated version of the same file
						if ($data['filename_play'] == '' || $data['filename_play'] == $data['filename']) {
							$data['filename_play']		=  $data['filename'];
						} else {
							$uploadPAP = 0;
						}
					} else if ($canPreview == 1) {
						// set new file only no other is set or it is the same like currect - to overwrite updated version of the same file
						if ($data['filename_preview'] == '' || $data['filename_preview'] == $data['filename']) {
							$data['filename_preview']	=  $data['filename'];
						} else {
							$uploadPAP = 0;
						}
					}
				}

				if ($uploadPAP == 0) {

				} else {
					if (!JFolder::exists($filepathPAPFolder)) {
						if (JFolder::create($filepathPAPFolder)) {
							$dataFile = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
							JFile::write($filepathPAPFolder . '/' ."index.html", $dataFile);
						}
						// else {
							//$errUploadMsg = JText::_("COM_PHOCADOWNLOAD_UNABLE_TO_CREATE_FOLDER");
							//return false;
						//}
					}

					if (!JFile::copy($filepath, $filepathPAP)) {
						//$errUploadMsg = JText::_("COM_PHOCADOWNLOAD_UNABLE_TO_UPLOAD_FILE");
						//return false;
					} else {


						if ($canPlay == 1) {
							$data['filename_play']		=  $data['filename'];
						} else if ($canPreview == 1) {
							$data['filename_preview']	=  $data['filename'];
						}
					}
				}
			}
		}
		// ==============================================


		// Bind the data.
		if (!$table->bind($data)) {
			throw new Exception($table->getError(), 500);
			return false;
		}

		// Date - - - - -
		$nullDate	= $this->_db->getNullDate();
		$config 	= JFactory::getConfig();
		$tzoffset 	= $config->get('offset');
		//$date 		= JFactory::getDate($table->date, $tzoffset);
		//$table->date 	= $date->toSql();

		// Append time if not added to publish date
		//if (strlen(trim($table->publish_up)) <= 10) {
		//	$table->publish_up .= ' 00:00:00';
		//}
		//$date = JFactory::getDate($table->publish_up, $tzoffset);
		//$table->publish_up = $date->toSql();

		if ($table->id) {

			// Test Solution add date when it is removed
			if (!intval($table->date)) {
				$date	= JFactory::getDate();
				$table->date = $date->toSql();
			}

		} else {
			if (!intval($table->date)) {
				$date	= JFactory::getDate();
				$table->date = $date->toSql();
			}
		}

		if(intval($table->publish_up) == 0) {
			$table->publish_up = JFactory::getDate()->toSql();
		}

		// Handle never unpublish date
		if (trim($table->publish_down) == JText::_('Never') || trim( $table->publish_down ) == '') {
			$table->publish_down = $nullDate;
		} else {
			if (strlen(trim( $table->publish_down )) <= 10) {
				$table->publish_down .= ' 00:00:00';
			}
			//$date = JFactory::getDate($table->publish_down, $tzoffset);
			$date = JFactory::getDate($table->publish_down);
			$table->publish_down = $date->toSql();
		}
		// - - - - - -


		// if new item, order last in appropriate group
		if (!$table->id) {
			$where = 'catid = ' . (int) $table->catid ;
			$table->ordering = $table->getNextOrder( $where );
		}







		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check()) {
			throw new Exception($table->getError(), 500);
			return false;
		}

		// Trigger the onContentBeforeSave event.
		/* $result = $dispatcher->trigger($this->event_before_save, array($this->option.'.'.$this->name, $table, $isNew));
		if (in_array(false, $result, true)) {
			throw new Exception($table->getError(), 500);
			return false;
		} */

		// Store the data.
		if (!$table->store()) {
			throw new Exception($table->getError(), 500);
			return false;
		}

		// Store to ref table
		if (!isset($data['tags'])) {
			$data['tags'] = array();
		}
		if ((int)$table->id > 0) {
			PhocaDownloadTag::storeTags($data['tags'], (int)$table->id);
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

		$result 			= false;

		$paramsC 		= JComponentHelper::getParams('com_phocadownload');
		$deleteExistingFiles 	= $paramsC->get( 'delete_existing_files', 0 );

		if (count( $cid )) {
			\Joomla\Utilities\ArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			// - - - - - - - - - - - - -
			// Get all filenames we want to delete from database, we delete all thumbnails from server of this file
			$queryd = 'SELECT filename as filename FROM #__phocadownload WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery($queryd);
			$fileObject = $this->_db->loadObjectList();
			// - - - - - - - - - - - - -


			//Delete it from DB
			$query = 'DELETE FROM #__phocadownload'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->execute()) {
				throw new Exception($this->_db->getErrorMsg(), 500);
				return false;
			}

			//Delete tags from DB
			$query = 'DELETE FROM #__phocadownload_tags_ref'
				. ' WHERE fileid IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->execute()) {
				throw new Exception($this->_db->getErrorMsg(), 500);
				return false;
			}

			//Delete files from statistics table
			$query = 'DELETE FROM #__phocadownload_user_stat'
				. ' WHERE fileid IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->execute()) {
				throw new Exception($this->_db->getErrorMsg(), 500);
				return false;
			}

			// - - - - - - - - - - - - - -
			// DELETE FILES ON SERVER
			if ($deleteExistingFiles == 1) {
				$path	= PhocaDownloadPath::getPathSet();
				foreach ($fileObject as $key => $value) {
					//The file can be stored in other category - don't delete it from server because other category use it
					$querys = "SELECT id as id FROM #__phocadownload WHERE filename='".$value->filename."' ";
					$this->_db->setQuery($querys);
					$sameFileObject = $this->_db->loadObject();
					// same file in other category doesn't exist - we can delete it
					if (!$sameFileObject) {
						JFile::delete(JPath::clean($path['orig_abs_ds'].$value->filename));
					}
				}
			}

		}
		return true;
	}

	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId	= (int) $value;
		$app	= JFactory::getApplication();
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

		if (empty($categoryId)) {
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

	//	$i		= 0;

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
			$table->catid	= $categoryId;

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

			$newId = $table->get('id');

			// Add the new ID to the array
			$newIds[$pk]	= $newId;
			//$i++;
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	/**
	 * Batch move articles to a new category
	 *
	 * @param   integer  $value  The new category ID.
	 * @param   array    $pks    An array of row IDs.
	 *
	 * @return  booelan  True if successful, false otherwise and internal error is set.
	 *
	 * @since	11.1
	 */
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

		if (empty($categoryId)) {

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
			$table->catid = $categoryId;

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
		$this->_db->setQuery('SELECT MAX(ordering) FROM #__phocadownload WHERE catid='.(int)$categoryId);
		$max = $this->_db->loadResult();
		$ordering = $max + 1;
		return $ordering;
	}
	/*
	function copyQuick($cid) {
		$table = $this->getTable();


		foreach ($cid as $id) {
			$table->load($id);

			// Find last ordering
			$this->_db->setQuery('SELECT MAX(ordering) FROM #__phocadownload WHERE catid='.(int)$table->catid);
			$max = $this->_db->loadResult();
			$table->ordering = $max+1;
			// End Ordering
			$table->id 		= null;
			$table->hits	= 0;

			if ( !$table->check() ) {
				throw new Exception($this->_db->getErrorMsg(), 500);
				return false;
			}
			if ( !$table->store() ) {
				throw new Exception($this->_db->getErrorMsg(), 500);
				return false;
			}
		}
		return true;
	}*/

}
?>
