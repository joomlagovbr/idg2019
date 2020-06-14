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
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class PhocaDownloadCpModelPhocaDownloadM extends JModelAdmin
{
	
	protected $option 		= 'com_phocadownload';
	protected $text_prefix 	= 'com_phocadownload';
	public 		$typeAlias 	= 'com_phocadownload.phocadownloadm';
	
	protected $fileCount		= 0;
	protected $categoryCount	= 0;
	
	function __construct() {
		$this->fileCount 		= 0;
		$this->categoryCount 	= 0;
		parent::__construct();
	}
		
		function setFileCount($count) {
		$this->fileCount = $this->fileCount + $count;
	}
	
	function setCategoryCount($count) {
		$this->categoryCount = $this->categoryCount + $count;
	}
	
	function save($data) {	
	
		$app		= JFactory::getApplication();
	
		$foldercid	= $app->input->get('foldercid', array(), 'raw');
		$cid		= $app->input->get('cid', 0, 'raw');
		$data		= $app->input->get('jform', array(0), 'post', 'array');
		
		
		// =================================================
		// Make a copy for play and preview
		$paramsC 	= JComponentHelper::getParams('com_phocadownload') ;
		$papCopy 	= $paramsC->get( 'pap_copy', 0 );
		$data['pap_copy_m'] = 0;
		if ($papCopy == 2 || $papCopy == 3) {
			$data['pap_copy_m'] = 1;
		}
		// =================================================
		
		
		if(isset($foldercid)) {
			$data['foldercid']	= $foldercid;
		}
		if(isset($cid)) {
			$data['cid']		= $cid;
		}
		
		if (isset($data['catid']) && (int)$data['catid'] > 0) {
			$data['catid']		= (int)$data['catid'];
		} else {
			$data['catid']		= 0;
		}
		
		//Get folder variables from Helper
		$path 			= PhocaDownloadPath::getPathSet();
		$origPath 		= $path['orig_abs_ds'];
		$origPathServer = str_replace('\\', '/', JPath::clean($path['orig_abs_ds']));
		
		
		
		// Cache all existing categories	
		$query = 'SELECT id, title, parent_id'
	    . ' FROM #__phocadownload_categories' ;
		$this->_db->setQuery( $query );
	    $existingCategories = $this->_db->loadObjectList() ;
		
		// Cache all existing files
		$query = 'SELECT catid, filename'
	    . ' FROM #__phocadownload';	    
		$this->_db->setQuery( $query );
	    $existingFiles = $this->_db->loadObjectList() ;
		
		$result					= new StdClass();
		$result->category_count = 0;
		$result->image_count 	= 0;
		
	
		
		// Category will be saved - Files will be saved in recursive function
		if (isset($data['foldercid'])) {
			foreach ($data['foldercid'] as $foldername) {
				if (strlen($foldername) > 0) {
					$fullPath 		= $path['orig_abs_ds'].$foldername;
				
					$result 		= $this->_createCategoriesRecursive( $origPathServer, $fullPath, $existingCategories, $existingFiles, $data['catid'], $data );					
				}		
			}
		}
		
		// Only Files will be saved
	
		if (!empty($data['cid'])) {
		
			// Make a copy for play and preview (1) --------
			if (isset($data['pap_copy_m']) && $data['pap_copy_m'] == 1) {
				//$paramsC 				= JComponentHelper::getParams('com_phocadownload') ;
				$overwriteExistingFiles = $paramsC->get( 'overwrite_existing_files', 0 );
			}
			// ------------------------------------------------
			
			foreach ($data['cid'] as $filename) {				
				if ($filename) {
					//$ext = strtolower(JFile::getExt($filename));
					$row = $this->getTable('phocadownload');
					
					$datam = array();
					$datam['published']		= $data['published'];
					$datam['catid']			= $data['catid'];
					$datam['approved']		= $data['approved'];
					$datam['language']		= $data['language'];
					$datam['filename']		= $filename;
					
					
					if ($data['title']	!= '') {
						$datam['title']		= $data['title'];
					} else {
						$datam['title']		= PhocaDownloadFile::getTitleFromFilenameWithoutExt($filename);
					}
					
					if ($data['alias']	!= '') {
						$datam['alias']		= $data['alias'];
					} else {
						$datam['alias']		= $data['alias']; // PhocaDownloadFile::getAliasName($datam['title']);
					}
					$datam['token']			= PhocaDownloadUtils::getToken($datam['title'].$datam['filename']);
					// Make a copy for play and preview (2)
					if (isset($data['pap_copy_m']) && $data['pap_copy_m'] == 1) {
						$filenameExt 		= PhocaDownloadFile::getTitleFromFilenameWithExt($filename);
						$storedfilename		= $filename;
						$storedfoldername	= str_replace($filenameExt, '', $storedfilename);
						$o = $this->_copyPreviewAndPlay($filenameExt, $storedfilename, $storedfoldername, $path, $overwriteExistingFiles);
						$datam['filename_play']		= $o['filename_play'];
						$datam['filename_preview'] 	= $o['filename_preview'];
					}
				
					// Save
					// Bind the form fields to the Phoca download table
					if (!$row->bind($datam)) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}

					// Create the timestamp for the date
					$row->date = gmdate('Y-m-d H:i:s');

					// if new item, order last in appropriate group
				
					if (!$row->id) {
						$where = 'catid = ' . (int) $row->catid ;
						$row->ordering = $row->getNextOrder( $where );
					}
					

					// Make sure the Phoca download table is valid
					if (!$row->check()) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}

					// Store the Phoca download table to the database
					if (!$row->store()) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}
					$result->image_count++;
					
				}
			}
			$this->setfileCount($result->image_count);

		}
		
		$msg = $this->categoryCount. ' ' .JText::_('COM_PHOCADOWNLOAD_CATEGORIES_ADDED') .', '.$this->fileCount. ' ' . JText::_('COM_PHOCADOWNLOAD_FILES_ADDED');
		$app->enqueueMessage($msg);
		$app->redirect(JRoute::_('index.php?option=com_phocadownload&view=phocadownloadfiles', false));
		
		return true;
		
	}
	
	protected function _createCategoriesRecursive(&$origPathServer, $path, &$existingCategories, &$existingFiles, $parentId = 0, $data = array() ) {
		
		$totalresult					= new StdClass();
		$totalresult->files_count 		= 0 ;
		$totalresult->category_count	= 0 ;
		$totalresult->image_count		= 0 ;
				
		$categoryName 	= basename($path);
		$id 			= $this->_getCategoryId( $existingCategories, $categoryName, $parentId ) ;
		$category 		= null;

		// Full path: eg. "/home/www/joomla/files/categ/subcat/"
		$fullPath	   	= str_replace('\\', '/', JPath::clean('/' . $path));
		// Relative path eg "categ/subcat"
		$relativePath 	= str_replace($origPathServer, '', $fullPath);	
		
		// Category doesn't exist
		if ( $id == -1 ) {
		  $row = $this->getTable('phocadownloadcat');
		  
		  $row->published 	= $data['published'];
		 // $row->approved	= $data['approved'];
		  $row->language	= $data['language'];
		  $row->parent_id 	= $parentId;
		  $row->title 		= $categoryName;
		  
		  // Create the timestamp for the date
		  $row->date 		= gmdate('Y-m-d H:i:s');
		 // $row->alias 		= $row->title; //PhocaDownloadFile::getAliasName($categoryName);
		  //$row->userfolder	= ltrim(str_replace('\\', '/', JPath::clean($relativePath )), '/');
		  $row->ordering 	= $row->getNextOrder( "parent_id = " . $this->_db->Quote($row->parent_id) );				
		
		  if (!$row->check()) {
			throw new Exception($row->getError('Check Problem'), 500);
			return false;
		  }

		  if (!$row->store()) {
			throw new Exception($row->getError('Check Problem'), 500);
			return false;
		  }
		  
		  $category 			= new JObject();
		  $category->title 		= $categoryName ;
		  $category->parent_id 	= $parentId;
		  $category->id 		= $row->id;
		  $totalresult->category_count++;
		  $id = $category->id;
		  $existingCategories[] = &$category ;
		  $this->setCategoryCount(1);//This subcategory was added
		}
		
		

		// Add all files from this folder
		$totalresult->image_count += $this->_addAllFilesFromFolder( $existingFiles, $id, $path, $relativePath, $data );
		$this->setfileCount($totalresult->image_count);
		
		// Do sub folders
		$parentId 		= $id;		
		$folderList 	= JFolder::folders( $path, $filter = '.', $recurse = false, $fullpath = true, $exclude = array() );		
		// Iterate over the folders if they exist
		if ($folderList !== false) {
			foreach ($folderList as $folder) {
				//$this->setCategoryCount(1);//This subcategory was added
				$folderName = $relativePath .'/' . str_replace($origPathServer, '', $folder);
				$result = $this->_createCategoriesRecursive( $origPathServer, $folder, $existingCategories, $existingFiles, $id , $data);
				$totalresult->image_count += $result->image_count ;
				$totalresult->category_count += $result->category_count ;
			}
		}
		return $totalresult ;
	}
	
	protected function _getCategoryId( &$existingCategories, &$title, $parentId ) {
	    $id = -1;
		$i 	= 0;
		$count = count($existingCategories);
		while ( $id == -1 && $i < $count ) {
		
			if ( $existingCategories[$i]->title == $title &&
			     $existingCategories[$i]->parent_id == $parentId ) {
				$id = $existingCategories[$i]->id ;
			}
			$i++;
		}
		return $id;
	}
	
	protected function _FileExist( &$existing_image, &$filename, $catid ) {
	    $result = false ;
		$i 		= 0;
		$count = count($existing_image);
		
		while ( $result == false && $i < $count ) {
			if ( $existing_image[$i]->filename == $filename &&
			     $existing_image[$i]->catid == $catid ) {
				$result = true;
			}
			$i++;
		}
		return $result;
	}
	
	protected function _addAllFilesFromFolder(&$existingImages, $category_id, $fullPath, $rel_path, $data = array()) {
		$count = 0;
		$fileList = JFolder::files( $fullPath );
		natcasesort($fileList);
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img
		
		// Make a copy for play and preview (1) --------
		if (isset($data['pap_copy_m']) && $data['pap_copy_m'] == 1) {
			$path					= PhocaDownloadPath::getPathSet();
			$storedfoldername		= ltrim(str_replace('\\', '/', JPath::clean($rel_path  )), '/');
			$paramsC 				= JComponentHelper::getParams('com_phocadownload') ;
			$overwriteExistingFiles = $paramsC->get( 'overwrite_existing_files', 0 );
		}
		// ------------------------------------------------

		if ($fileList !== false) {
			foreach ($fileList as $filename) {
			    $storedfilename	= ltrim(str_replace('\\', '/', JPath::clean($rel_path . '/' . $filename )), '/');
				
				//$ext = strtolower(JFile::getExt($filename));
								
				if (JFile::exists($fullPath.'/'.$filename) && 
					substr($filename, 0, 1) != '.' && 
					strtolower($filename) !== 'index.html' &&
					!$this->_FileExist($existingImages, $storedfilename, $category_id) ) {
					
					$row = $this->getTable('phocadownload');
					
					$datam = array();
					$datam['published']		= $data['published'];
					$datam['catid']			= $category_id;
					$datam['filename']		= $storedfilename;
					$datam['approved']		= $data['approved'];
					$datam['language']		= $data['language'];
					
					if ($data['title']	!= '') {
						$datam['title']		= $data['title'];
					} else {
						$datam['title']		= PhocaDownloadFile::getTitleFromFilenameWithoutExt($filename);
					}
					
					if ($data['alias']	!= '') {
						$datam['alias']		= $data['alias'];
					} else {
						$datam['alias']		= $data['alias'];//PhocaDownloadFile::get AliasName($datam['title']);
					}
					$datam['token']			= PhocaDownloadUtils::getToken($datam['title'].$datam['filename']);
					
					$image 				= new JObject();
					
					// Make a copy for play and preview (2)
					if (isset($data['pap_copy_m']) && $data['pap_copy_m'] == 1) {
						$o = $this->_copyPreviewAndPlay($filename, $storedfilename, $storedfoldername, $path, $overwriteExistingFiles);
						$datam['filename_play']		= $o['filename_play'];
						$datam['filename_preview'] 	= $o['filename_preview'];
					}
					
					
					// Save
					// Bind the form fields to the Phoca download table
					if (!$row->bind($datam)) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}

					// Create the timestamp for the date
					$row->date = gmdate('Y-m-d H:i:s');

					// if new item, order last in appropriate group
					if (!$row->id) {
						$where = 'catid = ' . (int) $row->catid ;
						$row->ordering = $row->getNextOrder( $where );
					}

					// Make sure the Phoca download table is valid
					if (!$row->check()) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}

					// Store the Phoca download table to the database
					if (!$row->store()) {
						throw new Exception($this->_db->getErrorMsg(), 500);
						return false;
					}
					// --------------------------------------------
									
					/*if ($this->firstImageFolder == '') {
						$this->setFirstImageFolder($row->filename);
					}*/
					
					$image->filename 	= $storedfilename;
					$image->catid 		= $category_id;
					
					$existingImages[] 	= &$image ;
					$count++ ;
					
				}
				 
			}
		}
		
	//	$this->setfileCount($count);
		return $count;
	}
	
	protected function _copyPreviewAndPlay($filename, $storedfilename, $storedfoldername, $path, $overwriteExistingFiles) {
		
		$o['filename_play']		= '';
		$o['filename_preview'] 	= '';
		$canPlay			= PhocaDownloadFile::canPlay($filename);
		$canPreview 		= PhocaDownloadFile::canPreview($filename);
		$filepathPAP 		= JPath::clean($path['orig_abs_pap_ds']. $storedfilename);
		//$filepathUserFolderPAP 		= JPath::clean($path['orig_abs_pap_ds']. $storedfoldername);
		$filepath 			= JPath::clean($path['orig_abs_ds']. $storedfilename);
		$filepathPAPFolder	= JPath::clean($path['orig_abs_pap_ds'] . '/'. PhocaDownloadFile::getFolderFromTheFile($storedfilename));

		if ($canPlay || $canPreview) {
			
			$uploadPAP = 1;// upload file for preview and play
			if (JFile::exists($filepathPAP) && $overwriteExistingFiles == 0) {
				//$errUploadMsg = JText::_("COM_PHOCADOWNLOAD_FILE_ALREADY_EXISTS");
				//return false;
				$uploadPAP = 0; // don't upload if it exists, it is not main file, don't do false and exit
				
				if ($canPlay == 1) {
					$o['filename_play']		=  $storedfilename;
				} else if ($canPreview == 1) {
					$o['filename_preview']	=  $storedfilename;
				}
			}
			
			// Overwrite file and add no new item to database
			$fileExistsPAP = 0;
			if (JFile::exists($filepathPAP) && $overwriteExistingFiles == 1) {
				$fileExistsPAP = 1;
				if ($canPlay == 1) {
					$o['filename_play']		=  $storedfilename;
				} else if ($canPreview == 1) {
					$o['filename_preview']	=  $storedfilename;
				}
			}
			
			if ($uploadPAP == 0) {
			
			} else {
				
				// First create folder if not exists
				if (!JFolder::exists($filepathPAPFolder)) {
					if (JFolder::create($filepathPAPFolder)) {
						$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						JFile::write($filepathPAPFolder . '/' ."index.html", $data);
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
					// Saving file name into database with relative path
					/*if (!JFile::exists($filepathUserFolderPAP . '/' ."index.html")) {
						$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						JFile::write($filepathUserFolderPAP . '/' ."index.html", $data);
					}*/
					
					if ($canPlay == 1) {
						//$image->filename_play		=  $storedfilename;
						$o['filename_play']		=  $storedfilename;
					} else if ($canPreview == 1) {
						//$image->filename_preview	=  $storedfilename;
						$o['filename_preview']	=  $storedfilename;
					}
				}
			}
		}
		return $o;
	}

	
	public function getForm($data = array(), $loadData = true) {
		
		$form 	= $this->loadForm('com_phocadownload.phocadownloadmanager', 'phocadownloadmanager', array('control' => 'jform', 'load_data' => $loadData));		
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	public function getTable($type = 'PhocaDownload', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownloadm.edit.phocadownloadm.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

}
?>