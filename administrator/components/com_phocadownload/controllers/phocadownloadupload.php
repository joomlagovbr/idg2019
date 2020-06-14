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
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.client.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class PhocaDownloadCpControllerPhocaDownloadUpload extends PhocaDownloadCpController
{
	function __construct() {
		parent::__construct();
	}

	function createfolder() {
		$app	= JFactory::getApplication();
		// Check for request forgeries
		JSession::checkToken() or jexit( 'COM_PHOCADOWNLOAD_INVALID_TOKEN' );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		$paramsC = JComponentHelper::getParams('com_phocadownload');
		$folder_permissions = $paramsC->get( 'folder_permissions', 0755 );
		//$folder_permissions = octdec((int)$folder_permissions);


		$folderNew		= JFactory::getApplication()->input->getCmd( 'foldername', '');
		$folderCheck	= JFactory::getApplication()->input->get( 'foldername', null, 'string');
		$parent			= JFactory::getApplication()->input->get( 'folderbase', '', 'path' );
		$tab			= JFactory::getApplication()->input->get( 'tab', 0, 'string' );
		$field			= JFactory::getApplication()->input->get( 'field');
		$viewBack		= JFactory::getApplication()->input->get( 'viewback', '', 'phocadownloadmanager' );
		$manager		= JFactory::getApplication()->input->get( 'manager', 'file', 'string' );


		$link = '';
		if ($manager != '') {
			$group 	= PhocaDownloadSettings::getManagerGroup($manager);
			$link	= 'index.php?option=com_phocadownload&view='.(string)$viewBack.'&manager='.(string)$manager
						 .str_replace('&amp;', '&', $group['c']).'&folder='.$parent.'&tab='.(string)$tab.'&field='.$field;

			$path	= PhocaDownloadPath::getPathSet($manager);// we use viewback to get right path
		} else {

			$app->enqueueMessage( JText::_('COM_PHOCADOWNLOAD_ERROR_CONTROLLER_MANAGER_NOT_SET'));
			$app->redirect('index.php?option=com_phocadownload');
			exit;
		}

		JFactory::getApplication()->input->set('folder', $parent);

		if (($folderCheck !== null) && ($folderNew !== $folderCheck)) {
			$app->enqueueMessage( JText::_('COM_PHOCADOWNLOAD_WARNING_DIRNAME'));
			$app->redirect($link);
		}


		if (strlen($folderNew) > 0) {
			$folder = JPath::clean($path['orig_abs_ds'].$parent.'/'.$folderNew);

			if (!JFolder::exists($folder) && !JFile::exists($folder)) {
				//JFolder::create($path, $folder_permissions );

				switch((int)$folder_permissions) {
					case 777:
						JFolder::create($folder, 0777 );
					break;
					case 705:
						JFolder::create($folder, 0705 );
					break;
					case 666:
						JFolder::create($folder, 0666 );
					break;
					case 644:
						JFolder::create($folder, 0644 );
					break;
					case 755:
					Default:
						JFolder::create($folder, 0755 );
					break;
				}
				if (isset($folder)) {
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($folder.'/'."index.html", $data);
				} else {
					$app->redirect($link, JText::_('COM_PHOCADOWNLOAD_ERROR_FOLDER_CREATING'));
				}

				$app->redirect($link, JText::_('COM_PHOCADOWNLOAD_SUCCESS_FOLDER_CREATING'));
			} else {
				$app->redirect($link, JText::_('COM_PHOCADOWNLOAD_ERROR_FOLDER_CREATING_EXISTS'));
			}
			//JFactory::getApplication()->input->set('folder', ($parent) ? $parent.'/'.$folder : $folder);
		}
		$app->redirect($link);
	}

	function multipleupload() {
		$result = PhocaDownloadFileUpload::realMultipleUpload();
		return true;
	}

	function upload() {
		$result = PhocaDownloadFileUpload::realSingleUpload();
		return true;
	}


}
