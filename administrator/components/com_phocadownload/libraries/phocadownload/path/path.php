<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaDownloadPath
{
	public static function getPathSet( $manager = '') {
	
		$group = PhocaDownloadSettings::getManagerGroup($manager);
		
		// Params
		$paramsC			= JComponentHelper::getParams( 'com_phocadownload' );
		// Folder where to stored files for download
		$downloadFolder		= $paramsC->get( 'download_folder', 'phocadownload' );
		$downloadFolderPap	= $paramsC->get( 'download_folder_pap', 'phocadownloadpap' );
		// Absolute path which can be outside public_html - if this will be set, download folder will be ignored
		$absolutePath		= $paramsC->get( 'absolute_path', '' );
		
		// Path of preview and play
		$downloadFolderPap 			= JPath::clean($downloadFolderPap);
		$path['orig_abs_pap'] 		= JPATH_ROOT .  '/' . $downloadFolderPap;
		$path['orig_abs_pap_ds'] 	= $path['orig_abs_pap'] . '/' ;
	
		if ($group['f'] == 2) {
			// Images
			$path['orig_abs'] 				= JPATH_ROOT . '/' . 'images/phocadownload' ;
			$path['orig_abs_ds'] 			= $path['orig_abs'] . '/' ;
			$path['orig_abs_user_upload'] 	= $path['orig_abs'] . '/' . 'userupload' ;
			$path['orig_abs_user_upload_pap']= $path['orig_abs_pap'] . '/' . 'userupload' ;
			$path['orig_rel_ds'] 			= '../images/phocadownload/';
		} else if ($group['f'] == 3) {
			// Play and Preview
			$path['orig_abs'] 				= $path['orig_abs_pap'];
			$path['orig_abs_ds'] 			= $path['orig_abs_pap_ds'];
			$path['orig_abs_user_upload'] 	= $path['orig_abs'] . '/' . 'userupload' ;
			$path['orig_abs_user_upload_pap']= $path['orig_abs_pap'] . '/' . 'userupload' ;
			$path['orig_rel_ds'] 			= '../'.str_replace('\\', '/', JPath::clean($downloadFolderPap)).'/';
		} else {
			// Standard Path	
			if ($absolutePath != '') {
				$downloadFolder 				= str_replace('\\', '/', JPath::clean($absolutePath));
				$path['orig_abs'] 				= str_replace('\\', '/', JPath::clean($absolutePath));
				$path['orig_abs_ds'] 			= JPath::clean($path['orig_abs'] . '/') ;
				$path['orig_abs_user_upload'] 	= JPath::clean($path['orig_abs'] . '/' . 'userupload') ;
				$path['orig_abs_user_upload_pap']= JPath::clean($path['orig_abs_pap'] . '/' . 'userupload') ;
				//$downloadFolderRel 	= str_replace('\\', '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 			= '';
				
			} else {
				$downloadFolder 				= str_replace('\\', '/', JPath::clean($downloadFolder));
				$path['orig_abs'] 				= JPATH_ROOT . '/' . $downloadFolder ;
				$path['orig_abs_ds'] 			= JPATH_ROOT . '/' . $downloadFolder . '/' ;
				$path['orig_abs_user_upload'] 	= $path['orig_abs'] . '/' . 'userupload' ;
				$path['orig_abs_user_upload_pap']= $path['orig_abs_pap'] . '/' . 'userupload' ;
				
				$downloadFolderRel 				= str_replace('\\', '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 			= '../' . $downloadFolderRel .'/';
			}
		}
		return $path;
	}
	
	public static function getPathMedia() {

		$option 						= 'com_phocadownload';
		$instance 						= new StdClass();
		$baseFront						= JURI::root(true);
		$instance->media_css_abs		= JPATH_ROOT . '/' . 'media'. '/' . $option . '/' . 'css' . '/';
		$instance->media_img_abs		= JPATH_ROOT . '/' . 'media'. '/' . $option . '/' . 'images' . '/';
		$instance->media_js_abs			= JPATH_ROOT . '/' . 'media'. '/' . $option . '/' . 'js' . '/';
		$instance->media_css_rel		= 'media/'. $option .'/css/';
		$instance->media_img_rel		= 'media/'. $option .'/images/';
		$instance->media_js_rel			= 'components/'. $option .'/assets/';
		$instance->media_css_rel_full	= $baseFront  . '/' . $instance->media_css_rel;
		$instance->media_img_rel_full	= $baseFront  . '/' . $instance->media_img_rel;
		$instance->media_js_rel_full	= $baseFront  . '/' . $instance->media_js_rel;
		return $instance;
	
	}
}
?>