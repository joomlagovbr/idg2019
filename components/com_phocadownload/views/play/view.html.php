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
jimport( 'joomla.application.component.view');

class PhocaDownloadViewPlay extends JViewLegacy
{

	function display($tpl = null){


		$app			= JFactory::getApplication();
		$params 		= $app->getParams();
		$this->t			= array();
		$this->t['user'] 	= JFactory::getUser();
		$uri 			= \Joomla\CMS\Uri\Uri::getInstance();
		$model			= $this->getModel();
		$document		= JFactory::getDocument();
		$fileId			= $app->input->get('id', 0, 'int');
		$file			= $model->getFile($fileId);

		$fileExt		= '';

		$filePath	= PhocaDownloadPath::getPathSet('fileplay');
		$filePath	= str_replace ( '../', JURI::base(false).'', $filePath['orig_rel_ds']);
		if (isset($file[0]->filename_play) && $file[0]->filename_play != '') {

			$fileExt = PhocaDownloadFile::getExtension($file[0]->filename_play);
			$canPlay	= PhocaDownloadFile::canPlay($file[0]->filename_play);
			if ($canPlay) {
				$this->t['playfilewithpath']	= $filePath . $file[0]->filename_play;
				//$this->t['playerpath']		= JURI::base().'components/com_phocadownload/assets/jwplayer/';
				$this->t['playerpath']			= JURI::base().'components/com_phocadownload/assets/flowplayer/';
				$this->t['playerwidth']			= $params->get( 'player_width', 328 );
				$this->t['playerheight']		= $params->get( 'player_height', 200 );
				$this->t['html5_play']			= $params->get( 'html5_play', 1 );
			} else {
				echo JText::_('COM_PHOCADOWNLOAD_ERROR_NO_CORRECT_FILE_TO_PLAY_FOUND');exit;
			}
		} else {
			echo JText::_('COM_PHOCADOWNLOAD_ERROR_NO_FILE_TO_PLAY_FOUND');exit;
		}

		$this->t['filetype']	= $fileExt;
		if ($fileExt == 'mp3') {
			$this->t['filetype'] 		= 'mp3';
			$this->t['playerheight']	= $params->get( 'player_mp3_height', 30 );
		} else if ($fileExt == 'ogg') {
			$this->t['filetype'] 		= 'ogg';
			$this->t['playerheight']	= $params->get( 'player_mp3_height', 30 );
		}


        $this->t['file'] = $file;
		//$this->assignRef('file',			$file);
		//$this->assignRef('tmpl',			$this->t);
		//$this->assignRef('params',			$params);
		//$uriT = $uri->toString();
		//$this->assignRef('request_url',		$uriT);
		parent::display($tpl);
	}
}
?>
