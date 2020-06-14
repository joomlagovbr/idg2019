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

class PhocaDownloadViewCategory extends JViewLegacy
{
	protected $category;
	protected $subcategories;
	protected $files;
	protected $t;

	function display($tpl = null) {

		$app					= JFactory::getApplication();
		$this->t['p'] 			= $app->getParams();
		$this->t['user'] 		= JFactory::getUser();
		$uri 					= \Joomla\CMS\Uri\Uri::getInstance();
		$model					= $this->getModel();
		$document				= JFactory::getDocument();
		$this->t['categoryid']	= $app->input->get( 'id', 0, 'int' );
		$this->t['tagid']		= $app->input->get( 'tagid', 0, 'int' );

		//if ($this->t['categoryid'] == 0 && $this->t['tagid'] == 0) {
			//throw new Exception(JText::_('COM_PHOCADOWNLOAD_CATEGORY_NOT_FOUND'), 404);
		//}


		$limitStart				= $app->input->get( 'limitstart', 0, 'int' );
		$this->t['mediapath']	= PhocaDownloadPath::getPathMedia();

		$this->category			= $model->getCategory($this->t['categoryid']);
		$this->subcategories	= $model->getSubcategories($this->t['categoryid']);
		$this->files			= $model->getFileList($this->t['categoryid'], $this->t['tagid']);
		$this->t['pagination']	= $model->getPagination($this->t['categoryid'], $this->t['tagid']);

		PhocaDownloadRenderFront::renderAllCSS();
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::root().'components/com_phocadownload/assets/overlib/overlib_mini.js"></script>');

		if ($limitStart > 0 ) {
			$this->t['limitstarturl'] =  '&start='.$limitStart;
		} else {
			$this->t['limitstarturl'] = '';
		}

		$this->t['download_external_link'] = $this->t['p']->get( 'download_external_link', '_self' );
		$this->t['filename_or_name'] 		= $this->t['p']->get( 'filename_or_name', 'filenametitle' );
		$this->t['display_downloads'] 		= $this->t['p']->get( 'display_downloads', 0 );
		$this->t['display_description'] 	= $this->t['p']->get( 'display_description', 3 );
		$this->t['display_detail'] 			= $this->t['p']->get( 'display_detail', 1 );
		$this->t['display_play'] 			= $this->t['p']->get( 'display_play', 0 );
		$this->t['playerwidth']				= $this->t['p']->get( 'player_width', 328 );
		$this->t['playerheight']			= $this->t['p']->get( 'player_height', 200 );
		$this->t['playermp3height']			= $this->t['p']->get( 'player_mp3_height', 30 );
		$this->t['previewwidth']			= $this->t['p']->get( 'preview_width', 640 );
		$this->t['previewheight']			= $this->t['p']->get( 'preview_height', 480 );
		$this->t['display_preview'] 		= $this->t['p']->get( 'display_preview', 0 );
		$this->t['play_popup_window'] 		= $this->t['p']->get( 'play_popup_window', 0 );
		$this->t['preview_popup_window'] 	= $this->t['p']->get( 'preview_popup_window', 0 );
		$this->t['file_icon_size'] 			= $this->t['p']->get( 'file_icon_size', 16 );
		$this->t['displaynew']				= $this->t['p']->get( 'display_new', 0 );
		$this->t['displayhot']				= $this->t['p']->get( 'display_hot', 0 );
		$this->t['display_up_icon'] 		= $this->t['p']->get( 'display_up_icon', 1 );
		$this->t['allowed_file_types']		= $this->t['p']->get( 'allowed_file_types', '' );
		$this->t['disallowed_file_types']	= $this->t['p']->get( 'disallowed_file_types', '' );
		$this->t['enable_user_statistics']	= $this->t['p']->get( 'enable_user_statistics', 1 );
		$this->t['display_category_comments']= $this->t['p']->get( 'display_category_comments', 0 );
		$this->t['display_date_type'] 		= $this->t['p']->get( 'display_date_type', 0 );
		$this->t['display_file_view']		= $this->t['p']->get('display_file_view', 0);
		$this->t['download_metakey'] 		= $this->t['p']->get( 'download_metakey', '' );
		$this->t['download_metadesc'] 		= $this->t['p']->get( 'download_metadesc', '' );
		$this->t['display_rating_file'] 	= $this->t['p']->get( 'display_rating_file', 0 );
		$this->t['display_mirror_links'] 	= $this->t['p']->get( 'display_mirror_links', 0 );
		$this->t['display_report_link'] 	= $this->t['p']->get( 'display_report_link', 0 );
		$this->t['send_mail_download'] 		= $this->t['p']->get( 'send_mail_download', 0 );// not boolean but id of user
		//$this->t['send_mail_upload'] 		= $this->t['p']->get( 'send_mail_upload', 0 );
		$this->t['display_tags_links'] 		= $this->t['p']->get( 'display_tags_links', 0 );
		$this->t['display_specific_layout']	= $this->t['p']->get( 'display_specific_layout', 0 );
		$this->t['fb_comment_app_id']		= $this->t['p']->get( 'fb_comment_app_id', '' );
		$this->t['fb_comment_width']		= $this->t['p']->get( 'fb_comment_width', '550' );
		$this->t['fb_comment_lang'] 		= $this->t['p']->get( 'fb_comment_lang', 'en_US' );
		$this->t['fb_comment_count'] 		= $this->t['p']->get( 'fb_comment_count', '' );
		$this->t['html5_play']				= $this->t['p']->get( 'html5_play', 1 );
		$this->t['bt_cat_col_left']			= (int)$this->t['p']->get( 'bt_cat_col_left', 6 );
		if ($this->t['bt_cat_col_left'] == 12) {
			$this->t['bt_cat_col_right']		= 12;
		} else {
			$this->t['bt_cat_col_right']		= 12 - $this->t['bt_cat_col_left'];
		}

		// Rating
		if ($this->t['display_rating_file'] == 1 || $this->t['display_rating_file'] == 3) {
			JHtml::_('jquery.framework', false);
			PhocaDownloadRate::renderRateFileJS(1);
			$this->t['display_rating_file'] = 1;
		} else {
			$this->t['display_rating_file'] = 0;
		}

		$this->t['afd']						= PhocaDownloadRenderFront::renderPhocaDownload();

		// DOWNLOAD
		// - - - - - - - - - - - - - - -
		$download	= $app->input->get( 'download', array(0),'array' );
		$downloadId	= (int) $download[0];
		if ($downloadId > 0) {
			if (isset($this->category[0]->id) && (int)$this->category[0]->id > 0 ) {
				$currentLink	= 'index.php?option=com_phocadownload&view=category&id='.$this->category[0]->id.':'.$this->category[0]->alias.$this->t['limitstarturl'] . '&Itemid='. $app->input->get('Itemid', 0, 'int');
			} else {
				$currentLink = $uri;
			}
			$fileData		= PhocaDownloadDownload::getDownloadData($downloadId, $currentLink);
			PhocaDownloadDownload::download($fileData, $downloadId, $currentLink);
		}
		// - - - - - - - - - - - - - - -

		// DETAIL
		// - - - - - - - - - - - - - - -

		// BOOTSTRAP
		$this->t['bootstrapmodal'] 		= '';
		PhocaDownloadRenderFront::renderBootstrapModalJs('.pd-modal-button');

		if ($this->t['display_detail'] == 2) {
			$this->t['buttond'] = new JObject();
			$this->t['buttond']->set('methodname', 'modal-button');
			$this->t['buttond']->set('name', 'detail');
			$this->t['buttond']->set('modal', true);
			$this->t['buttond']->set('options', "{handler: 'iframe', size: {x: 600, y: 500}, overlayOpacity: 0.7, classWindow: 'phocadownloaddetailwindow', classOverlay: 'phocadownloaddetailoverlay'}");

			// BOOTSTRAP
			$this->t['bootstrapmodal'] .= PhocaDownloadRenderFront::bootstrapModalHtml('phModalDetail' , JText::_('COM_PHOCADOWNLOAD_DETAILS'));
		} else if ($this->t['display_detail'] == 3) {

			$this->t['buttond'] = new JObject();
			$this->t['buttond']->set('methodname', 'js-button');
			$this->t['buttond']->set('name', 'detail');
			$this->t['buttond']->set('options', "window.open(this.href,'win2','width=600,height=500,scrollbars=yes,menubar=no,resizable=yes'); return false;");

		}


		// PLAY - - - - - - - - - - - -
		$windowWidthPl 		= (int)$this->t['playerwidth'] + 20;
		$windowHeightPl 	= (int)$this->t['playerheight'] + 20;

		if ($this->t['html5_play'] == 1) {
			$windowWidthPl 		= (int)$this->t['playerwidth'] + 50;
		} else {
			$windowWidthPl 		= (int)$this->t['playerwidth'] + 50;
		}
		$windowHeightPlMP3 	= (int)$this->t['playermp3height'] + 30;
		if ($this->t['play_popup_window'] == 1) {
			$this->t['buttonpl'] = new JObject();
			$this->t['buttonpl']->set('methodname', 'js-button');
			$this->t['buttonpl']->set('options', "window.open(this.href,'win2','width=".$windowWidthPl.",height=".$windowHeightPl.",scrollbars=yes,menubar=no,resizable=yes'); return false;");
			$this->t['buttonpl']->set('optionsmp3', "window.open(this.href,'win2','width=".$windowWidthPl.",height=".$windowHeightPlMP3.",scrollbars=yes,menubar=no,resizable=yes'); return false;");
		} else {
			$document->addCustomTag( "<style type=\"text/css\"> \n"
		." #sbox-window.phocadownloadplaywindow   {background-color:#fff;padding:2px} \n"
		." #sbox-overlay.phocadownloadplayoverlay  {background-color:#000;} \n"
		." </style> \n");
			$this->t['buttonpl'] = new JObject();
			$this->t['buttonpl']->set('name', 'image');
			$this->t['buttonpl']->set('modal', true);
			$this->t['buttonpl']->set('methodname', 'modal-button');
			$this->t['buttonpl']->set('options', "{handler: 'iframe', size: {x: ".$windowWidthPl.", y: ".$windowHeightPl."}, overlayOpacity: 0.7, classWindow: 'phocadownloadplaywindow', classOverlay: 'phocadownloadplayoverlay'}");
			$this->t['buttonpl']->set('optionsmp3', "{handler: 'iframe', size: {x: ".$windowWidthPl.", y: ".$windowHeightPlMP3."}, overlayOpacity: 0.7, classWindow: 'phocadownloadplaywindow', classOverlay: 'phocadownloadplayoverlay'}");

			// BOOTSTRAP
			$this->t['buttonpl']->set('optionsB', ' data-width-dialog="'.$windowWidthPl.'" data-height-dialog="'.$windowHeightPl.'"');
			$this->t['buttonpl']->set('optionsmp3B', ' data-width-dialog="'.$windowWidthPl.'" data-height-dialog="'.$windowHeightPlMP3.'"');
			$this->t['bootstrapmodal'] .= PhocaDownloadRenderFront::bootstrapModalHtml('phModalPlay' , JText::_('COM_PHOCADOWNLOAD_PLAY'));


		}
		// - - - - - - - - - - - - - - -
		// PREVIEW - - - - - - - - - - - -
		$windowWidthPr 	= (int)$this->t['previewwidth'] + 20;
		$windowHeightPr = (int)$this->t['previewheight'] + 20;
		if ($this->t['preview_popup_window'] == 1) {
			$this->t['buttonpr'] = new JObject();
			$this->t['buttonpr']->set('methodname', 'js-button');
			$this->t['buttonpr']->set('options', "window.open(this.href,'win2','width=".$windowWidthPr.",height=".$windowHeightPr.",scrollbars=yes,menubar=no,resizable=yes'); return false;");
		} else {
			$document->addCustomTag( "<style type=\"text/css\"> \n"
		." #sbox-window.phocadownloadpreviewwindow   {background-color:#fff;padding:2px} \n"
		." #sbox-overlay.phocadownloadpreviewoverlay  {background-color:#000;} \n"
		." </style> \n");
			$this->t['buttonpr'] = new JObject();
			$this->t['buttonpr']->set('name', 'image');
			$this->t['buttonpr']->set('modal', true);
			$this->t['buttonpr']->set('methodname', 'modal-button');
			$this->t['buttonpr']->set('options', "{handler: 'iframe', size: {x: ".$windowWidthPr.", y: ".$windowHeightPr."}, overlayOpacity: 0.7, classWindow: 'phocadownloadpreviewwindow', classOverlay: 'phocadownloadpreviewoverlay'}");
			$this->t['buttonpr']->set('optionsimg', "{handler: 'image', size: {x: 200, y: 150}, overlayOpacity: 0.7, classWindow: 'phocadownloadpreviewwindow', classOverlay: 'phocadownloadpreviewoverlay'}");

			// BOOTSTRAP
			$this->t['buttonpr']->set('optionsB', ' data-type="document" data-width-dialog="'.$windowWidthPr.'" data-height-dialog="'.$windowHeightPr.'"');
			$this->t['buttonpr']->set('optionsimgB', 'data-type="image"');
			$this->t['bootstrapmodal'] .= PhocaDownloadRenderFront::bootstrapModalHtml('phModalPreview' , JText::_('COM_PHOCADOWNLOAD_PREVIEW'));
		}
		// - - - - - - - - - - - - - - -

		$this->t['ordering']	= $model->getFileOrderingSelect();

		$imagePath				= PhocaDownloadPath::getPathSet('icon');
		$this->t['cssimgpath']	= str_replace ( '../', JURI::base(true).'/', $imagePath['orig_rel_ds']);
		$filePath				= PhocaDownloadPath::getPathSet('file');
		$this->t['absfilepath']	= $filePath['orig_abs_ds'];
		$this->t['action']		= $uri->toString();



		// Bootstrap 3 Layout
		$this->t['display_bootstrap3_layout']	= $this->t['p']->get( 'display_bootstrap3_layout', 0 );
		if ((int)$this->t['display_bootstrap3_layout'] > 0) {

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
		} else {
			// Because of modals
			JHTML::_('behavior.framework', true);
			JHTML::_('behavior.modal', 'a.pd-modal-button');
		}

		if (isset($this->category[0]) && is_object($this->category[0])){
			$this->_prepareDocument($this->category[0]);
		}

		if ($this->t['display_bootstrap3_layout'] > 0) {
			parent::display('bootstrap');
		} else {
			parent::display($tpl);
		}

	}

	protected function _prepareDocument($category) {

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		//$this->t['p']		= &$app->getParams();
		$title 		= null;

		$this->t['downloadmetakey'] 					= $this->t['p']->get( 'download_metakey', '' );
		$this->t['downloadmetadesc'] 					= $this->t['p']->get( 'download_metadesc', '' );
		$this->t['disable_breadcrumbs_category_view'] 	= $this->t['p']->get( 'disable_breadcrumbs_category_view',0 );


		$menu = $menus->getActive();
		if ($menu) {
			$this->t['p']->def('page_heading', $this->t['p']->get('page_title', $menu->title));
		} else {
			$this->t['p']->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		/*
		$title = $this->t['p']->get('page_title', '');

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
		$this->t['display_cat_name_title'] = 1;
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);

			if ($this->t['display_cat_name_title'] == 1 && isset($category->title) && $category->title != '') {
				$title = $title .' - ' .  $category->title;
			}

		} else if ($app->get('sitename_pagetitles', 0) == 2) {

			if ($this->t['display_cat_name_title'] == 1 && isset($category->title) && $category->title != '') {
				$title = $title .' - ' .  $category->title;
			}

			$title = JText::sprintf('JPAGETITLE', $title, htmlspecialchars_decode($app->get('sitename')));
		}
		$this->document->setTitle($title);

		if ($category->metadesc != '') {
			$this->document->setDescription($category->metadesc);
		} else if ($this->t['downloadmetadesc'] != '') {
			$this->document->setDescription($this->t['downloadmetadesc']);
		} else if ($this->t['p']->get('menu-meta_description', '')) {
			$this->document->setDescription($this->t['p']->get('menu-meta_description', ''));
		}

		if ($category->metakey != '') {
			$this->document->setMetadata('keywords', $category->metakey);
		} else if ($this->t['downloadmetakey'] != '') {
			$this->document->setMetadata('keywords', $this->t['downloadmetakey']);
		} else if ($this->t['p']->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->t['p']->get('menu-meta_keywords', ''));
		}

		if ($app->get('MetaTitle') == '1' && $this->t['p']->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->t['p']->get('page_title', ''));
		}

		// Breadcrumbs TO DO (Add the whole tree)
		/*$pathway 		= $app->getPathway();
		if (isset($this->category[0]->parentid)) {
			if ($this->category[0]->parentid == 0) {
				// $pathway->addItem( JText::_('COM_PHOCADOWNLOAD_CATEGORIES'), JRoute::_(PhocaDownloadRoute::getCategoriesRoute()));
			} else if ($this->category[0]->parentid > 0) {
				$pathway->addItem($this->category[0]->parenttitle, JRoute::_(PhocaDownloadRoute::getCategoryRoute($this->category[0]->parentid, $this->category[0]->parentalias)));
			}
		}

		if (!empty($this->category[0]->title)) {
			$pathway->addItem($this->category[0]->title);
		}*/

		// Breadcrumbs TO DO (Add the whole tree)


		// Start comment if problem with duplicated pathway
		if ($this->t['disable_breadcrumbs_category_view'] == 1) {
			if (isset($this->category[0]->parentid)) {
				if ($this->category[0]->parentid == 0) {
					// $pathway->addItem( JText::_('COM_PHOCADOWNLOAD_CATEGORIES'), JRoute::_(PhocaDownloadRoute::getCategoriesRoute()));

				} else if ($this->category[0]->parentid > 0) {
					$curpath = $pathway->getPathwayNames();

					if(isset($this->category[0]->parenttitle) && isset($curpath[count($curpath)-1]) && $this->category[0]->parenttitle != $curpath[count($curpath)-1]){


					 	$pathway->addItem($this->category[0]->parenttitle, JRoute::_(PhocaDownloadRoute::getCategoryRoute($this->category[0]->parentid, $this->category[0]->parentalias)));
					}
				}
			}
		}
		// End comment when problem with duplicated pathway

		if (!empty($this->category[0]->title)) {
			$curpath = $pathway->getPathwayNames();
			if(isset($this->category[0]->title) && isset($curpath[count($curpath)-1]) && $this->category[0]->title != $curpath[count($curpath)-1]){
				$pathway->addItem($this->category[0]->title);
			}
		}



	}
}
?>
