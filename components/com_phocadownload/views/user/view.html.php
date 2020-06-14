<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.client.helper' );
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );
use Joomla\String\StringHelper;

class PhocaDownloadViewUser extends JViewLegacy
{
	protected $_context_files			= 'com_phocadownload.phocadownloaduserfiles';
	protected $t;
	function display($tpl = null) {

		$app				= JFactory::getApplication();
		$document			= JFactory::getDocument();
		$uri 				= \Joomla\CMS\Uri\Uri::getInstance();
		$menus				= $app->getMenu();
		$menu				= $menus->getActive();
		$this->t['p']		= $app->getParams();
		$user 				= JFactory::getUser();
		$db					=  JFactory::getDBO();
		$user 				= JFactory::getUser();
		$userLevels			= implode (',', $user->getAuthorisedViewLevels());

		$this->t['pi']		= 'media/com_phocadownload/images/';
		$this->t['pp']		= 'index.php?option=com_phocadownload&view=user&controller=user';
		$this->t['pl']		= 'index.php?option=com_users&view=login&return='.base64_encode($this->t['pp'].'&Itemid='. $app->input->get('Itemid', 0, 'int'));

		$neededAccessLevels	= PhocaDownloadAccess::getNeededAccessLevels();
		$access				= PhocaDownloadAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);

		if (!$access) {
			$app->redirect(JRoute::_($this->t['pl'], false), JText::_("COM_PHOCADOWNLOAD_NOT_AUTHORISED_ACTION"));
			return;
		}

		PhocaDownloadRenderFront::renderAllCSS();

		JHtml::_('jquery.framework', false);
		$document	= JFactory::getDocument();
		$document->addScriptDeclaration(
		'jQuery(document).ready(function(){
			jQuery(\'.phfileuploadcheckcat\').click(function(){
			if( !jQuery(\'#catid\').val() || jQuery(\'#catid\').val() == 0) { 
				alert(\''.JText::_('COM_PHOCADOWNLOAD_PLEASE_SELECT_CATEGORY').'\'); return false;
			} else {
				return true;
			}
		})});'
		);


		// = = = = = = = = = = =
		// PANE
		// = = = = = = = = = = =
		// - - - - - - - - - -
		// ALL TABS
		// - - - - - - - - - -
		// UCP is disabled (security reasons)
		if ((int)$this->t['p']->get( 'enable_user_cp', 0 ) == 0) {
			$app->redirect(JURI::base(false), JText::_("COM_PHOCADOWNLOAD_USER_UPLOAD_DISABLED"));
			exit;
		}

		$this->t['tab'] 					= $app->input->get('tab', 0, 'string');
		$this->t['maxuploadchar']			= $this->t['p']->get( 'max_upload_char', 1000 );
		$this->t['enableuseruploadapprove']	= $this->t['p']->get( 'enable_user_upload_approve', 0 );
		$this->t['showpageheading'] 		= $this->t['p']->get( 'show_page_heading', 1 );
		$this->t['uploadmaxsize'] 			= $this->t['p']->get( 'user_file_upload_size', 3145728 );
		$this->t['uploadmaxsizeread']		= PhocaDownloadFile::getFileSizeReadable($this->t['uploadmaxsize']);
		$this->t['userfilesmaxcount']		= $this->t['p']->get( 'user_files_max_count', 5 );
		$this->t['userfilesmaxsize']		= $this->t['p']->get( 'user_files_max_size', 20971520 );
		$this->t['send_mail_upload'] 		= $this->t['p']->get( 'send_mail_upload', 0 );
		$this->t['pw']						= PhocaDownloadRenderFront::renderPhocaDownload();
		//Subcateogry
		//$this->t['parentid']			= $app->input->get('parentcategoryid', 0, 'int');

		//$document->addScript(JURI::base(true).'/components/com_phocadownload/assets/js/comments.js');
		$document->addCustomTag(PhocaDownloadRenderFront::renderOnUploadJS());
		$document->addCustomTag(PhocaDownloadRenderFront::renderDescriptionUploadJS((int)$this->t['maxuploadchar']));
		$document->addCustomTag(PhocaDownloadRenderFront::userTabOrdering());
		$model 			= $this->getModel('user');

		// Upload Form - - - - - - - - - - - - - - -
		$ftp = !JClientHelper::hasCredentials('ftp');// Set FTP form
		$session = JFactory::getSession();
		$this->t['session'] = $session;
		// END Upload Form - - - - - - - - - - - - -

		$this->t['displayupload'] = 1;



		// - - - - - - - - - -
		// FORM
		// - - - - - - - - - -
		// No Controller because of returning back the values in case some form field is not OK

		// Set default for returning back
		$formData = new JObject();
		$formData->set('title', '');
		$formData->set('description','');
		$formData->set('author','');
		$formData->set('email','');
		$formData->set('license','');
		$formData->set('website','');
		$formData->set('version','');

		$this->t['errorcatid'] 		= '';
		$this->t['erroremail'] 		= '';
		$this->t['errorwebsite'] 	= '';
		$this->t['errorfile'] 		= '';

		$task 	= $app->input->get( 'task', '', 'string' );

		if($task == 'upload') {
			$post['title']			= $app->input->get( 'phocadownloaduploadtitle', '', 'string' );
			$post['description']	= $app->input->get( 'phocadownloaduploaddescription', '', 'string' );
			$post['catidfiles']		= $app->input->get( 'catidfiles', 0, 'int' );
			$post['description']	= substr($post['description'], 0, (int)$this->t['maxuploadchar']);

			$post['approved']		= 0;
			$post['published']		= 1;
			$post['owner_id']		= $user->id;
			if ($this->t['enableuseruploadapprove'] == 0) {
				$post['approved']	= 1;
			}
			$post['author']		= $app->input->get( 'phocadownloaduploadauthor', '', 'string' );
			$post['email']		= $app->input->get( 'phocadownloaduploademail', '', 'string' );
			$post['website']	= $app->input->get( 'phocadownloaduploadwebsite', '', 'string' );
			$post['license']	= $app->input->get( 'phocadownloaduploadlicense', '', 'string' );
			$post['version']	= $app->input->get( 'phocadownloaduploadversion', '', 'string' );

			if ($post['title'] != '')		{$formData->set('title', $post['title']);}
			if ($post['description'] != '')	{$formData->set('description', $post['description']);}
			if ($post['author'] != '')		{$formData->set('author', $post['author']);}
			if ($post['email'] != '')		{$formData->set('email', $post['email']);}
			if ($post['website'] != '')		{$formData->set('website', $post['website']);}
			if ($post['license'] != '')		{$formData->set('license', $post['license']);}
			if ($post['version'] != '')		{$formData->set('version', $post['version']);}

			//catid
			$returnForm = 0;
			if ($post['catidfiles'] < 1) {
				$this->t['errorcatid'] = JText::_('COM_PHOCADOWNLOAD_PLEASE_SELECT_CATEGORY');
				$returnForm = 1;
			}
			jimport('joomla.mail.helper');
			if ($post['email'] != '' && !JMailHelper::isEmailAddress($post['email']) ) {
				$this->t['erroremail'] = JText::_('COM_PHOCADOWNLOAD_PLEASE_ENTER_VALID_EMAIL_ADDRESS');
				$returnForm = 1;
			}
			if ($post['website'] != '' && !PhocaDownloadUtils::isURLAddress($post['website']) ) {
				$this->t['errorwebsite'] = JText::_('COM_PHOCADOWNLOAD_PLEASE_ENTER_VALID_WEBSITE');
				$returnForm = 1;
			}

			// Upload
			$errUploadMsg	= '';
			$redirectUrl 	= '';

			$fileArray 		= JFactory::getApplication()->input->files->get( 'Filedata', null, 'raw');

			if(empty($fileArray)) {

				$this->t['errorfile'] = JText::_('COM_PHOCADOWNLOAD_PLEASE_ADD_FILE_OR_IF_ADDED_CHECK_IF_IT_HAS_RIGHT_FORMAT_AND_SIZE');
				$returnForm = 1;

			} else if (isset($fileArray[0]) && $fileArray[0] == ''){
				$this->t['errorfile'] = JText::_('COM_PHOCADOWNLOAD_PLEASE_ADD_FILE_OR_IF_ADDED_CHECK_IF_IT_HAS_RIGHT_FORMAT_AND_SIZE');
				$returnForm = 1;
				$fileArray['name'] = '';

			} else if (isset($fileArray['name']) && $fileArray['name'] == '') {

				$this->t['errorfile'] = JText::_('COM_PHOCADOWNLOAD_PLEASE_ADD_FILE_OR_IF_ADDED_CHECK_IF_IT_HAS_RIGHT_FORMAT_AND_SIZE');
				$returnForm = 1;
			}

			if ($post['title'] == '') {
				$post['title']	= PhocaDownloadFile::removeExtension($fileArray['name']);
			}
			$post['alias'] 	= PhocaDownloadUtils::getAliasName($post['title']);


			if ($returnForm == 0) {
				$errorUploadMsg = '';
				if($model->singleFileUpload($errorUploadMsg, $fileArray, $post)) {

					if ($this->t['send_mail_upload'] > 0) {
						PhocaDownloadMail::sendMail((int)$this->t['send_mail_upload'], $post['title'], 2);
					}

					$Itemid		= $app->input->get( 'Itemid', 0, 'int');
					$limitStart	= $app->input->get( 'limitstart', 0, 'int');
					if ($limitStart > 0) {
						$limitStartUrl	= '&limitstart='.$limitStart;
					} else {
						$limitStartUrl	= '';
					}
					$link = 'index.php?option=com_phocadownload&view=user&Itemid='. $Itemid . $limitStartUrl;
					$app->redirect(JRoute::_($link, false), JText::_("COM_PHOCADOWNLOAD_SUCCESS_FILE_UPLOADED"));
					exit;
				} else {
					$this->t['errorfile'] = JText::_('COM_PHOCADOWNLOAD_ERROR_FILE_UPLOADED');
					if ($errorUploadMsg != '') {
						$this->t['errorfile'] .= '<br />' . $errorUploadMsg;
					}
				}

			}
		}


		// - - - - - - - - - - -
		// FILES
		// - - - - - - - - - - -
		$this->t['filesitems'] 		= $model->getDataFiles($user->id);
		$this->t['filestotal'] 		= $model->getTotalFiles($user->id);
		$this->t['filespagination'] 	= $model->getPaginationFiles($user->id);

		$filter_state_files		= $app->getUserStateFromRequest( $this->_context_files.'.filter_state','filter_state', '','word');
		$filter_catid_files		= $app->getUserStateFromRequest( $this->_context_files.'.filter_catid','filter_catid',0, 'int' );
		$catid_files			= $app->getUserStateFromRequest( $this->_context_files. '.catid',	'catid', 0,	'int');
		//$filter_sectionid_files	= $app->getUserStateFromRequest( $this->_context_files.'.filter_sectionid',	'filter_sectionid',	0,	'int' );
		$filter_order_files		= $app->getUserStateFromRequest( $this->_context_files.'.filter_order','filter_order','a.ordering', 'cmd' );
		$filter_order_Dir_files	= $app->getUserStateFromRequest( $this->_context_files.'.filter_order_Dir','filter_order_Dir',	'',	'word' );
		$search_files			= $app->getUserStateFromRequest( $this->_context_files.'.search', 'search', '', 'string' );
		$search_files			= StringHelper::strtolower( $search_files );

		// build list of categories
		$javascript 	= 'class="inputbox" size="1" onchange="document.phocadownloadfilesform.submit();"';

		// get list of categories for dropdown filter
		$whereC		= array();
		//if ($filter_sectionid_files > 0) {
		//	$whereC[] = ' cc.section = '.$db->Quote($filter_sectionid_files);
		//}
		//$whereC[]	= "(cc.uploaduserid LIKE '%-1%' OR cc.uploaduserid LIKE '%".(int)$user->id."%')";
		//$whereC[]	= "(cc.uploaduserid LIKE '%-1%' OR cc.uploaduserid LIKE '%,{".(int)$user->id."}' OR cc.uploaduserid LIKE '{".(int)$user->id."},%' OR cc.uploaduserid LIKE '%,{".(int)$user->id."},%' OR cc.uploaduserid ={".(int)$user->id."} )";
		$whereC[]	= "(cc.uploaduserid LIKE '%-1%' OR cc.uploaduserid LIKE '%,".(int)$user->id."' OR cc.uploaduserid LIKE '".(int)$user->id.",%' OR cc.uploaduserid LIKE '%,".(int)$user->id.",%' OR cc.uploaduserid =".(int)$user->id." )";
		$whereC 		= ( count( $whereC ) ? ' WHERE '. implode( ' AND ', $whereC ) : '' );

		// get list of categories for dropdown filter
		$query = 'SELECT cc.id AS value, cc.title AS text, cc.parent_id as parentid' .
				' FROM #__phocadownload_categories AS cc' .
				$whereC.
				' ORDER BY cc.ordering';

		$lists_files['catid'] = PhocaDownloadCategory::filterCategory($query, $catid_files, TRUE, TRUE, TRUE);


		/*$whereS		= array();
		//$whereS[]	= "(cc.uploaduserid LIKE '%-1%' OR cc.uploaduserid LIKE '%".(int)$user->id."%')";
		$whereS[]	= "(cc.uploaduserid LIKE '%-1%' OR cc.uploaduserid LIKE '%,".(int)$user->id."' OR cc.uploaduserid LIKE '".(int)$user->id.",%' OR cc.uploaduserid LIKE '%,".(int)$user->id.",%' OR cc.uploaduserid =".(int)$user->id." )";
		$whereS[]	= 's.published = 1';
		$whereS 		= ( count( $whereS ) ? ' WHERE '. implode( ' AND ', $whereS ) : '' );
		// sectionid
		$query = 'SELECT s.title AS text, s.id AS value'
		. ' FROM #__phocadownload_sections AS s'
		. ' LEFT JOIN #__phocadownload_categories AS cc ON cc.section = s.id'
		. $whereS
		. ' GROUP BY s.id'
		. ' ORDER BY s.ordering';



		// state filter
	/*	$state_files[] 		= JHTML::_('select.option',  '', '- '. JText::_( 'Select State' ) .' -' );
		$state_files[] 		= JHTML::_('select.option',  'P', JText::_( 'Published' ) );
		$state_files[] 		= JHTML::_('select.option',  'U', JText::_( 'Unpublished') );
		$lists_image['state']	= JHTML::_('select.genericlist',   $state_files, 'filter_state', 'class="inputbox" size="1" onchange="document.phocadownloadfilesform.submit();"', 'value', 'text', $filter_state );*/

		//$lists_files['sectionid'] = PhocaDownloadCategory::filterSection($query, $filter_sectionid_files, TRUE);

		// state filter
		$lists_files['state']	= JHTML::_('grid.state',  $filter_state_files );

		// table ordering
		$lists_files['order_Dir'] = $filter_order_Dir_files;
		$lists_files['order'] = $filter_order_files;

		// search filter
		$lists_files['search']= $search_files;

		$this->t['catidfiles']			= $catid_files;

		$this->t['filestab'] 			= 1;

		// Tabs
		$displayTabs	= 0;
		if ((int)$this->t['filestab'] == 0) {
			$currentTab['files'] = -1;
		} else {
			$currentTab['files'] = $displayTabs;
			$displayTabs++;
		}

		$this->t['displaytabs']	= $displayTabs;
		$this->t['currenttab']		= $currentTab;


		// ACTION
		$this->t['action']	= $uri->toString();
		// SEF problem
		$isThereQM = false;
		$isThereQM = preg_match("/\?/i", $this->t['action']);
		if ($isThereQM) {
			$amp = '&amp;';
		} else {
			$amp = '?';
		}
		$this->t['actionamp']	=	htmlspecialchars($this->t['action']) . $amp;
		$this->t['istheretab'] = false;
		$this->t['istheretab'] = preg_match("/tab=/i", $this->t['action']);


		$this->t['ps']	= '&tab='. $this->t['currenttab']['files']
			. '&limitstart='.$this->t['filespagination']->limitstart;


		// ASIGN
		//$this->assignRef( 'listsfiles',		$lists_files);
		$this->t['listsfiles'] = $lists_files;
		//$this->assignRef( 'formdata',		$formData);
		$this->t['formdata'] = $formData;
		//$this->assignRef( 'tmpl', $this->t);
		//$this->assignRef( 'params', $this->t['p']);
		//$session = JFactory::getSession();
		//$this->assignRef('session', $session);

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
			$document->addScript(JURI::root(true).'/media/com_phocadownload/js/jquery.equalheights.min.js');
			$document->addScriptDeclaration(
			'jQuery(window).load(function(){
				jQuery(\'.ph-thumbnail\').equalHeights();
			});');
		}

		if ($this->t['display_bootstrap3_layout'] > 0) {
			parent::display('bootstrap');
		} else {
			parent::display($tpl);
		}
	}
}
?>
