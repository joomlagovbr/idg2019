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

class PhocaDownloadCpViewPhocaDownloadManager extends JViewLegacy
{
	protected $field;
	protected $fce;
	protected $folderstate;
	protected $images;
	protected $folders;
	protected $tmpl;
	protected $session;
	protected $currentFolder;
	protected $t;

	public function display($tpl = null) {

		$this->t		= PhocaDownloadUtils::setVars('manager');
		$this->field	= JFactory::getApplication()->input->get('field');
		$this->fce 		= 'phocaSelectFileName_'.$this->field;

		JHTML::stylesheet( $this->t['s'] );


		$this->folderstate	= $this->get('FolderState');
		$this->files		= $this->get('Files');
		$this->folders		= $this->get('Folders');
		$this->session		= JFactory::getSession();
		$this->manager 		= JFactory::getApplication()->input->get( 'manager', '',  'file' );



		if ($this->manager == 'filemultiple') {
			$this->form			= $this->get('Form');
		}

		$params = JComponentHelper::getParams($this->t['o']);

		$this->t['multipleuploadchunk']	= $params->get( 'multiple_upload_chunk', 0 );
		$this->t['uploadmaxsize'] 		= $params->get( 'upload_maxsize', 3145728 );
		$this->t['uploadmaxsizeread'] 	= PhocaDownloadFile::getFileSizeReadable($this->t['uploadmaxsize']);
		$this->t['enablemultiple'] 		= $params->get( 'enable_multiple_upload_admin', 1 );
		$this->t['multipleuploadmethod'] = $params->get( 'multiple_upload_method', 4 );

		$this->currentFolder = '';
		if (isset($this->folderstate->folder) && $this->folderstate->folder != '') {
			$this->currentFolder = $this->folderstate->folder;
		}

		// - - - - - - - - - -
		//TABS
		// - - - - - - - - - -
		$this->t['tab'] 			= JFactory::getApplication()->input->get('tab', '', '', 'string');
		$this->t['displaytabs']	= 0;

		// UPLOAD
		$this->t['currenttab']['upload'] = $this->t['displaytabs'];
		$this->t['displaytabs']++;

		// MULTIPLE UPLOAD
		if((int)$this->t['enablemultiple']  >= 0) {
			$this->t['currenttab']['multipleupload'] = $this->t['displaytabs'];
			$this->t['displaytabs']++;
		}

		$group 	= PhocaDownloadSettings::getManagerGroup($this->manager);

		// - - - - - - - - - - -
		// Upload
		// - - - - - - - - - - -
		$sU							= new PhocaDownloadFileUploadSingle();
		$sU->returnUrl				= 'index.php?option=com_phocadownload&view=phocadownloadmanager&tab=upload'.str_replace('&amp;', '&', $group['c']).'&manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&folder='.PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath');
		$sU->tab					= 'upload';
		$this->t['su_output']	= $sU->getSingleUploadHTML();
		$this->t['su_url']		= JURI::base().'index.php?option=com_phocadownload&task=phocadownloadupload.upload&amp;'
								  .$this->session->getName().'='.$this->session->getId().'&amp;'
								  . JSession::getFormToken().'=1&amp;viewback=phocadownloadmanager&amp;manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&amp;field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&amp;'
								  .'folder='. PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath').'&amp;tab=upload';


		// - - - - - - - - - - -
		// Multiple Upload
		// - - - - - - - - - - -
		// Get infos from multiple upload
		$muFailed						= JFactory::getApplication()->input->get( 'mufailed', '0', '', 'int' );
		$muUploaded						= JFactory::getApplication()->input->get( 'muuploaded', '0', '', 'int' );
		$this->t['mu_response_msg']	= $muUploadedMsg 	= '';

		if ($muUploaded > 0) {
			$muUploadedMsg = JText::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded;
		}
		if ($muFailed > 0) {
			$muFailedMsg = JText::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed;
		}
		if ($muFailed > 0 && $muUploaded > 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-info">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded .'<br />'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'</div>';
		} else if ($muFailed > 0 && $muUploaded == 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'</div>';
		} else if ($muFailed == 0 && $muUploaded > 0){
			$this->t['mu_response_msg'] = '<div class="alert alert-success">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded.'</div>';
		} else {
			$this->t['mu_response_msg'] = '';
		}

		if((int)$this->t['enablemultiple']  >= 0) {

			PhocadownloadFileUploadMultiple::renderMultipleUploadLibraries();
			$mU						= new PhocaDownloadFileUploadMultiple();
			$mU->frontEnd			= 0;
			$mU->method				= $this->t['multipleuploadmethod'];
			$mU->url				= JURI::base().'index.php?option=com_phocadownload&task=phocadownloadupload.multipleupload&amp;'
									 .$this->session->getName().'='.$this->session->getId().'&'
									 . JSession::getFormToken().'=1&tab=multipleupload&manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&folder='. PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath');
			$mU->reload				= JURI::base().'index.php?option=com_phocadownload&view=phocadownloadmanager'
									.str_replace('&amp;', '&', PhocaDownloadUtils::filterValue($group['c'], 'text')).'&'
									.$this->session->getName().'='.$this->session->getId().'&'
									. JSession::getFormToken().'=1&tab=multipleupload&'
									.'manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&folder='. PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath');
			$mU->maxFileSize		= PhocadownloadFileUploadMultiple::getMultipleUploadSizeFormat($this->t['uploadmaxsize']);
			$mU->chunkSize			= '1mb';

			$mU->renderMultipleUploadJS(0, $this->t['multipleuploadchunk']);
			$this->t['mu_output']= $mU->getMultipleUploadHTML();
		}


		$this->t['ftp'] 			= !JClientHelper::hasCredentials('ftp');
		$this->t['path']			= PhocaDownloadPath::getPathSet($this->manager);

		$this->addToolbar();
		parent::display($tpl);
		echo JHTML::_('behavior.keepalive');
	}

	function setFolder($index = 0) {
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

	function setFile($index = 0) {
		if (isset($this->files[$index])) {
			$this->_tmp_file = &$this->files[$index];
		} else {
			$this->_tmp_file = new JObject;
		}
	}

	protected function addToolbar() {

		JFactory::getApplication()->input->set('hidemainmenu', true);
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['task'].'.php';
		$state	= $this->get('State');
		$class	= ucfirst($this->t['task']).'Helper';
		$canDo	= $class::getActions($this->t, $state->get('filter.multiple'));

		JToolbarHelper::title( JText::_( $this->t['l'].'_MULTIPLE_ADD' ), 'plus' );

		if ($canDo->get('core.create')){
			JToolbarHelper::save($this->t['c'].'m.save', 'JTOOLBAR_SAVE');
		}

		JToolbarHelper::cancel($this->t['c'].'m.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
