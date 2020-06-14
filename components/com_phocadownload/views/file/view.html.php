<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaDownloadViewFile extends JViewLegacy
{
	protected $file;
	protected $category;
	protected $t;

	function display($tpl = null){		
		
		$app					= JFactory::getApplication();
		$this->t['p'] 				= $app->getParams();
		$this->t['user'] 		= JFactory::getUser();
		$uri 					= \Joomla\CMS\Uri\Uri::getInstance();
		$model					= $this->getModel();
		$document				= JFactory::getDocument();
		$fileId					= $app->input->get('id', 0, 'int');
		
		//if ($fileId == 0) {
		//	throw new Exception(JText::_('COM_PHOCADOWNLOAD_FILE_NOT_FOUND'), 404);
		//}
		
		$this->t['limitstart']	= $app->input->get( 'start', 0, 'int');// we need it for category back link
		$this->t['tmpl']		= $app->input->get( 'tmpl', '', 'string' );
		$this->t['mediapath']	= PhocaDownloadPath::getPathMedia();

		$this->t['tmplr'] = 0;
		if ($this->t['tmpl'] == 'component') {
			$this->t['tmplr'] = 1;
		}

		if ($this->t['limitstart'] > 0 ) {
			$this->t['limitstarturl'] = '&start='.$this->t['limitstart'];
		} else {
			$this->t['limitstarturl'] = '';
		}
		
		$this->category			= $model->getCategory($fileId);
		$this->file				= $model->getFile($fileId, $this->t['limitstarturl']);
		
		PhocaDownloadRenderFront::renderAllCSS();
		
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::root().'components/com_phocadownload/assets/overlib/overlib_mini.js"></script>');
		$js	= 'var enableDownloadButtonPD = 0;'
			 .'function enableDownloadPD() {'
			 .' if (enableDownloadButtonPD == 0) {'
			 .'   document.forms[\'phocadownloadform\'].elements[\'pdlicensesubmit\'].disabled=false;'
			 .'   enableDownloadButtonPD = 1;'
			 .' } else {'
			 .'   document.forms[\'phocadownloadform\'].elements[\'pdlicensesubmit\'].disabled=true;'
			 .'   enableDownloadButtonPD = 0;'
			 .' }'
			 .'}';
		$document->addScriptDeclaration($js);

		
		// Params
		$this->t['licenseboxheight']		= $this->t['p']->get( 'license_box_height', 300 );
		$this->t['filename_or_name'] 		= $this->t['p']->get( 'filename_or_name', 'filename' );
		$this->t['display_up_icon'] 		= $this->t['p']->get( 'display_up_icon', 1 );
		$this->t['allowed_file_types']		= $this->t['p']->get( 'allowed_file_types', '' );
		$this->t['disallowed_file_types']	= $this->t['p']->get( 'disallowed_file_types', '' );
		$this->t['enable_user_statistics']	= $this->t['p']->get( 'enable_user_statistics', 1 );
		$this->t['display_file_comments'] 	= $this->t['p']->get( 'display_file_comments', 0 );
		$this->t['file_icon_size'] 			= $this->t['p']->get( 'file_icon_size', 16 );
		$this->t['display_file_view']		= $this->t['p']->get('display_file_view', 0);
		$this->t['download_metakey'] 		= $this->t['p']->get( 'download_metakey', '' );
		$this->t['download_metadesc'] 		= $this->t['p']->get( 'download_metadesc', '' );
		$this->t['display_downloads'] 		= $this->t['p']->get( 'display_downloads', 0 );
		$this->t['display_date_type'] 		= $this->t['p']->get( 'display_date_type', 0 );
		$this->t['displaynew']				= $this->t['p']->get( 'display_new', 0 );
		$this->t['displayhot']				= $this->t['p']->get( 'display_hot', 0 );
		$this->t['pw']						= PhocaDownloadRenderFront::renderPhocaDownload();
		$this->t['download_external_link'] 	= $this->t['p']->get( 'download_external_link', '_self' );
		$this->t['display_report_link'] 	= $this->t['p']->get( 'display_report_link', 0 );
		$this->t['send_mail_download'] 		= $this->t['p']->get( 'send_mail_download', 0 );// not boolean but id of user
		//$this->t['send_mail_upload'] 		= $this->t['p']->get( 'send_mail_upload', 0 );
		$this->t['display_rating_file'] 	= $this->t['p']->get( 'display_rating_file', 0 );
		$this->t['display_tags_links'] 		= $this->t['p']->get( 'display_tags_links', 0 );
		$this->t['display_mirror_links'] 	= $this->t['p']->get( 'display_mirror_links', 0 );
		$this->t['display_specific_layout']	= $this->t['p']->get( 'display_specific_layout', 0 );
		$this->t['display_detail']			= $this->t['p']->get( 'display_detail', 1);
		$this->t['fb_comment_app_id']		= $this->t['p']->get( 'fb_comment_app_id', '' );
		$this->t['fb_comment_width']		= $this->t['p']->get( 'fb_comment_width', '550' );
		$this->t['fb_comment_lang'] 		= $this->t['p']->get( 'fb_comment_lang', 'en_US' );
		$this->t['fb_comment_count'] 		= $this->t['p']->get( 'fb_comment_count', '' );
		
		// Rating
		if ($this->t['display_rating_file'] == 2 || $this->t['display_rating_file'] == 3 ) {
			JHTML::_('jquery.framework', true);
			PhocaDownloadRate::renderRateFileJS(1);
			$this->t['display_rating_file'] = 1;
		} else {
			$this->t['display_rating_file'] = 0;
		}

		// DOWNLOAD
		// - - - - - - - - - - - - - - - 
		$download				= $app->input->get( 'download', array(0), 'array' );
		$licenseAgree			= $app->input->get( 'license_agree', '', 'string' );
		$downloadId		 		= (int) $download[0];
		if ($downloadId > 0) {
			if (isset($this->file[0]->id)) {
				$currentLink	= 'index.php?option=com_phocadownload&view=file&id='.$this->file[0]->id.':'.$this->file[0]->alias. $this->t['limitstarturl'] . '&Itemid='. $app->input->get('Itemid', 0, 'int');
			} else {
				$currentLink	= 'index.php?option=com_phocadownload&view=categories&Itemid='. $app->input->get('Itemid', 0, 'int');
			}
		
			// Check Token
			if (!JSession::checkToken()) {
				
				$app->redirect(JRoute::_('index.php', false), JText::_('COM_PHOCADOWNLOAD_INVALID_TOKEN'));
				exit;
			}
			
			// Check License Agreement
			if (empty($licenseAgree)) {
				$app->redirect(JRoute::_($currentLink, false), JText::_('COM_PHOCADOWNLOAD_WARNING_AGREE_LICENSE_TERMS'));
				exit;
			}
			
			$fileData		= PhocaDownloadDownload::getDownloadData($downloadId, $currentLink);
			PhocaDownloadDownload::download($fileData, $downloadId, $currentLink);
		}
		// - - - - - - - - - - - - - - - 
		
		$imagePath				= PhocaDownloadPath::getPathSet('icon');
		$this->t['cssimgpath']	= str_replace ( '../', JURI::base(true).'/', $imagePath['orig_rel_ds']);
		$filePath				= PhocaDownloadPath::getPathSet('file');
		$this->t['absfilepath']	= $filePath['orig_abs_ds'];
		$this->t['action']		= $uri->toString();	
		
		if (isset($this->category[0]) && is_object($this->category[0]) && isset($this->file[0]) && is_object($this->file[0])){
			$this->_prepareDocument($this->category[0], $this->file[0]);
		}

		
		// Bootstrap 3 Layout
		$this->t['display_bootstrap3_layout']	= $this->t['p']->get( 'display_bootstrap3_layout', 0 );
		
		if ($this->t['display_bootstrap3_layout'] > 0) {
			
			JHtml::_('jquery.framework', false);
			if ((int)$this->t['display_bootstrap3_layout'] == 2) {
				JHTML::stylesheet('media/com_phocadownload/bootstrap/css/bootstrap.min.css' );
				JHTML::stylesheet('media/com_phocadownload/bootstrap/css/bootstrap.extended.css' );
			}
			// Loaded by jquery.framework;
			//$document->addScript(JURI::root(true).'/media/com_phocadownload/bootstrap/js/bootstrap.min.js');
			/*$document->addScript(JURI::root(true).'/media/com_phocadownload/js/jquery.equalheights.min.js');
			$document->addScriptDeclaration(
			'jQuery(window).load(function(){
				jQuery(\'.ph-thumbnail\').equalHeights();
			});');*/
		}
		
		if ($this->t['display_bootstrap3_layout'] > 0) {
			parent::display('bootstrap');	
		} else {
			parent::display($tpl);	
		}
		
	}
	
	protected function _prepareDocument($category, $file) {
		
		$app			= JFactory::getApplication();
		$menus			= $app->getMenu();
		$menu 			= $menus->getActive();
		$pathway 		= $app->getPathway();
		$title 			= null;
		
		$this->t['downloadmetakey'] 	= $this->t['p']->get( 'download_metakey', '' );
		$this->t['downloadmetadesc'] 	= $this->t['p']->get( 'download_metadesc', '' );
		
		if ($menu) {
			$this->t['p']->def('page_heading', $this->t['p']->get('page_title', $menu->title));
		} else {
			$this->t['p']->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		/*$title = $this->t['p']->get('page_title', '');
		if (empty($title) || (isset($title) && $title == '')) {
			$title = $this->item->title;
		}
		if (empty($title) || (isset($title) && $title == '')) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
		}
		//$this->document->setTitle($title);

		$this->document->setTitle($title);*/
		
		$title = $this->t['p']->get('page_title', '');
		$this->t['display_file_name_title'] = 1; 
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
			
			if ($this->t['display_file_name_title'] == 1 && isset($file->title) && $file->title != '') {
				$title = $title .' - ' .  $file->title;
			}
			
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			
			if ($this->t['display_file_name_title'] == 1 && isset($file->title) && $file->title != '') {
				$title = $title .' - ' .  $file->title;
			}

			$title = JText::sprintf('JPAGETITLE', $title, htmlspecialchars_decode($app->get('sitename')));
		}
		$this->document->setTitle($title);

		
		if ($file->metadesc != '') {
			$this->document->setDescription($file->metadesc);
		} else if ($this->t['downloadmetadesc'] != '') {
			$this->document->setDescription($this->t['downloadmetadesc']);
		} else if ($this->t['p']->get('menu-meta_description', '')) {
			$this->document->setDescription($this->t['p']->get('menu-meta_description', ''));
		} 

		if ($file->metakey != '') {
			$this->document->setMetadata('keywords', $file->metakey);
		} else if ($this->t['downloadmetakey'] != '') {
			$this->document->setMetadata('keywords', $this->t['downloadmetakey']);
		} else if ($this->t['p']->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->t['p']->get('menu-meta_keywords', ''));
		}

		if ($app->get('MetaTitle') == '1' && $this->t['p']->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->t['p']->get('page_title', ''));
		}
		
		// Breadcrumbs TO DO (Add the whole tree)
		$pathway 		= $app->getPathway();
		if (isset($category->id)) {
			if ($category->id > 0) {
				$pathway->addItem($category->title, JRoute::_(PhocaDownloadRoute::getCategoryRoute($category->id, $category->alias)));
			}
		}
		
		if (!empty($file->title)) {
			$pathway->addItem($file->title);
		}
	}
}
?>