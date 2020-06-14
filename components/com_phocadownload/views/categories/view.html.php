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
jimport( 'joomla.application.component.view');

class PhocaDownloadViewCategories extends JViewLegacy
{
	protected $t;

	function display($tpl = null)
	{		
		$app								= JFactory::getApplication();
		$model								= $this->getModel();
		$document							= JFactory::getDocument();
		$this->t['p'] 						= $app->getParams();
		$this->t['user'] 					= JFactory::getUser();	
		$this->t['categories']				= $model->getCategoriesList();
		$this->t['mostvieweddocs']			= $model->getMostViewedDocsList($this->t['p']);
		$this->t['dev']						= PhocaDownloadRenderFront::renderPhocaDownload();
		$this->t['displaynew']				= $this->t['p']->get( 'display_new', 0 );
		$this->t['displayhot']				= $this->t['p']->get( 'display_hot', 0 );
		$this->t['displaymostdownload']		= $this->t['p']->get( 'display_most_download', 1 );
		$this->t['displaynumdocsecs']		= $this->t['p']->get( 'display_num_doc_secs', 0 );
		$this->t['displaynumdocsecsheader']	= $this->t['p']->get( 'display_num_doc_secs_header', 1 );
		$this->t['file_icon_size_md'] 		= $this->t['p']->get( 'file_icon_size_md', 16 );
		$this->t['download_metakey'] 		= $this->t['p']->get( 'download_metakey', '' );
		$this->t['download_metadesc'] 		= $this->t['p']->get( 'download_metadesc', '' );
		$this->t['description']				= $this->t['p']->get( 'description', '' );
		$this->t['displaymaincatdesc']		= $this->t['p']->get( 'display_main_cat_desc', 0 );
		$this->t['display_specific_layout']	= $this->t['p']->get( 'display_specific_layout', 0 );
		
		

		
		// Bootstrap 3 Layout
		$this->t['display_bootstrap3_layout']	= $this->t['p']->get( 'display_bootstrap3_layout', 0 );
		if ((int)$this->t['display_bootstrap3_layout'] > 0) {
			
			JHtml::_('jquery.framework', false);
			if ((int)$this->t['display_bootstrap3_layout'] == 2) {
				JHTML::stylesheet('media/com_phocadownload/bootstrap/css/bootstrap.min.css' );
				JHTML::stylesheet('media/com_phocadownload/bootstrap/css/bootstrap.extended.css' );
				// Loaded by jquery.framework;
				$document->addScript(JURI::root(true).'/media/com_phocadownload/bootstrap/js/bootstrap.min.js');
			}
			
			$document->addScript(JURI::root(true).'/media/com_phocadownload/js/jquery.matchHeight.js');
			$document->addScriptDeclaration(
			'jQuery(window).load(function(){
				jQuery(\'.ph-thumbnail\').matchHeight();
			});');
			
			/*$document->addScript(JURI::root(true).'/media/com_phocadownload/js/jquery.equalheights.min.js');
			$document->addScriptDeclaration(
			'jQuery(window).load(function(){
				jQuery(\'.ph-thumbnail\').equalHeights();
			});');*/

		}
		
		PhocaDownloadRenderFront::renderAllCSS();
		
		$imagePath				= PhocaDownloadPath::getPathSet('icon');
		
		$this->t['cssimgpath']	= str_replace ( '../', JURI::base(true).'/', $imagePath['orig_rel_ds']);
		$filePath				= PhocaDownloadPath::getPathSet('file');
		$this->t['absfilepath']	= $filePath['orig_abs_ds'];

		$this->_prepareDocument();
		if ($this->t['display_bootstrap3_layout'] > 0) {
			parent::display('bootstrap');	
		} else {
			parent::display($tpl);	
		}
		
	}
	
	protected function _prepareDocument() {
		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$menu 		= $menus->getActive();
		$pathway 	= $app->getPathway();
		$title 		= null;
		
		$this->t['downloadmetakey'] 	= $this->t['p']->get( 'download_metakey', '' );
		$this->t['downloadmetadesc'] 	= $this->t['p']->get( 'download_metadesc', '' );
	
		if ($menu) {
			$this->t['p']->def('page_heading', $this->t['p']->get('page_title', $menu->title));
		} else {
			$this->t['p']->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}
/*
		$title = $this->t['p']->get('page_heading', '');
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
		}
		//$this->document->setTitle($title);

		if (empty($title) || (isset($title) && $title == '')) {
			$title = $this->item->title;
		}
		$this->document->setTitle($title);*/
		
		$title = $this->t['p']->get('page_title', '');		
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, htmlspecialchars_decode($app->get('sitename')));
		}
        $this->document->setTitle($title);
		

		
		if ($this->t['downloadmetadesc'] != '') {
			$this->document->setDescription($this->t['downloadmetadesc']);
		} else if ($this->t['p']->get('menu-meta_description', '')) {
			$this->document->setDescription($this->t['p']->get('menu-meta_description', ''));
		} 

		if ($this->t['downloadmetakey'] != '') {
			$this->document->setMetadata('keywords', $this->t['downloadmetakey']);
		} else if ($this->t['p']->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->t['p']->get('menu-meta_keywords', ''));
		}

		if ($app->get('MetaTitle') == '1' && $this->t['p']->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->t['p']->get('page_title', ''));
		}
	}
}
?>