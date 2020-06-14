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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaDownloadDownload
{
	public static function download($fileData, $downloadId, $currentLink, $type = 0) {
			
		$app			= JFactory::getApplication();
		$params 		= $app->getParams();
		$directLink 	= $fileData['directlink'];// Direct Link 0 or 1
		$externalLink 	= $fileData['externallink'];
		$absOrRelFile	= $fileData['file'];// Relative Path or Absolute Path
		
		// Type = 1 - Token - unique download link - cannot be direct
		if ($type == 1) {
			$directLink = 0;
		}
		
		// NO FILES FOUND (abs file)
		$error 			= false;
		$error 			= preg_match("/COM_PHOCADOWNLOAD_ERROR/i", $absOrRelFile);
		
		if ($error) {
			$msg = JText::_('COM_PHOCADOWNLOAD_ERROR_WHILE_DOWNLOADING_FILE') . ' ' . JText::_($absOrRelFile);
			$app->redirect(JRoute::_($currentLink), $msg);
		} else {
			
			// Get extensions
			$extension = JFile::getExt(strtolower($absOrRelFile));
			
			$aft = $params->get( 'allowed_file_types_download', PhocaDownloadSettings::getDefaultAllowedMimeTypesDownload() );
			$dft = $params->get( 'disallowed_file_types_download', '' );
			
			// Get Mime from params ( ext --> mime)
			$allowedMimeType 	= PhocaDownloadFile::getMimeType($extension, $aft);
			$disallowedMimeType = PhocaDownloadFile::getMimeType($extension, $dft);
			
			// NO MIME FOUND
			$errorAllowed 		= false;// !!! IF YES - Disallow Downloading
			$errorDisallowed 	= false;// !!! IF YES - Allow Downloading
			
			$errorAllowed 		= preg_match("/PhocaError/i", $allowedMimeType);
			$errorDisallowed	= preg_match("/PhocaError/i", $disallowedMimeType);
			
			$ignoreDownloadCheck = $params->get( 'ignore_file_types_check', 2 );
			if ($ignoreDownloadCheck == 3 || $ignoreDownloadCheck == 4 || $ignoreDownloadCheck == 5) {
				$errorAllowed = false;
				$errorDisallowed = true;
			}
			
			
			if ($errorAllowed) {
				$msg = JText::_('COM_PHOCADOWNLOAD_WARNFILETYPE_DOWNLOAD');
				$app->redirect(JRoute::_($currentLink), $msg);
			} else if (!$errorDisallowed) {
				$msg = JText::_('COM_PHOCADOWNLOAD_WARNFILETYPE_DISALLOWED_DOWNLOAD');
				$app->redirect(JRoute::_($currentLink), $msg);		
			} else {
				
				if ($directLink == 1) {
				
					// Direct Link on the same server
					$fileWithoutPath	= basename($absOrRelFile);
					$addHit				= self::hit($downloadId);
					if ($type == 1) {
						self::hitToken($downloadId);
					}
					
					if ((int)$params->get('send_mail_download', 0) > 0) {
						PhocaDownloadMail::sendMail((int)$params->get('send_mail_download', 0), $fileWithoutPath, 1);
					}
					
					// USER Statistics
					if ((int)$params->get('enable_user_statistics', 1) == 1) {
						$addUserStat = PhocaDownloadStat::createUserStatEntry($downloadId);
					}
					
					PhocaDownloadLog::log($downloadId, 1);
					
					
					$app->redirect ($absOrRelFile);
					exit;
				} else if ($directLink == 0 && $externalLink != '') {
					
					// External Link but with redirect
					// In case there is directLink the external Link does not go this way but directly to the external URL
					$addHit	= self::hit($downloadId);
					if ($type == 1) {
						self::hitToken($downloadId);
					}
					
					if ((int)$params->get('send_mail_download', 0) > 0) {
						PhocaDownloadMail::sendMail((int)$params->get('send_mail_download', 0), $externalLink, 1);
					}
					
					// USER Statistics
					if ((int)$params->get('enable_user_statistics', 1) == 1) {
						$addUserStat = PhocaDownloadStat::createUserStatEntry($downloadId);
					}
					
					PhocaDownloadLog::log($downloadId, 1);
					
					
					$app->redirect ($externalLink);
					exit;
				
				} else {
				
					// Clears file status cache
					clearstatcache();
					
					$fileWithoutPath	= basename($absOrRelFile);
					$fileSize 			= filesize($absOrRelFile);
					$mimeType			= '';
					$mimeType			= $allowedMimeType;
					
					// HIT Statistics
					$addHit	= self::hit($downloadId);
					if ($type == 1) {
						self::hitToken($downloadId);
					}
					
					if ((int)$params->get('send_mail_download', 0) > 0) {
						PhocaDownloadMail::sendMail((int)$params->get('send_mail_download', 0), $fileWithoutPath, 1);
					}
					
					// USER Statistics
					if ((int)$params->get('enable_user_statistics', 1) == 1) {
						$addUserStat = PhocaDownloadStat::createUserStatEntry($downloadId);
					}
					
					PhocaDownloadLog::log($downloadId, 1);
					
					
					if ($fileSize == 0 ) {
						die(JText::_('COM_PHOCADOWNLOAD_FILE_SIZE_EMPTY'));
						exit;
					}
					
					// Clean the output buffer
					ob_end_clean();
					
					// test for protocol and set the appropriate headers
				    jimport( 'joomla.environment.uri' );
				    $_tmp_uri 		= JURI::getInstance( JURI::current() );
				    $_tmp_protocol 	= $_tmp_uri->getScheme();
					if ($_tmp_protocol == "https") {
						// SSL Support
						header('Cache-Control: private, max-age=0, must-revalidate, no-store');
				    } else {
						header("Cache-Control: public, must-revalidate");
						header('Cache-Control: pre-check=0, post-check=0, max-age=0');
						header("Pragma: no-cache");
						header("Expires: 0");
					} /* end if protocol https */
					header("Content-Description: File Transfer");
					header("Expires: Sat, 30 Dec 1990 07:07:07 GMT");
					header("Accept-Ranges: bytes");

					
					// HTTP Range
				/*	$httpRange = 0;
					if(isset($_SERVER['HTTP_RANGE'])) {
						list($a, $httpRange) = explode('=', $_SERVER['HTTP_RANGE']);
						str_replace($httpRange, '-', $httpRange);
						$newFileSize	= $fileSize - 1;
						$newFileSizeHR	= $fileSize - $httpRange;
						header("HTTP/1.1 206 Partial Content");
						header("Content-Length: ".(string)$newFileSizeHR);
						header("Content-Range: bytes ".$httpRange . $newFileSize .'/'. $fileSize);
					} else {
						$newFileSize	= $fileSize - 1;
						header("Content-Length: ".(string)$fileSize);
						header("Content-Range: bytes 0-".$newFileSize . '/'.$fileSize);
					}
					header("Content-Type: " . (string)$mimeType);
					header('Content-Disposition: attachment; filename="'.$fileWithoutPath.'"');
					header("Content-Transfer-Encoding: binary\n");*/
					
					// Modified by Rene
					// HTTP Range - see RFC2616 for more informations (http://www.ietf.org/rfc/rfc2616.txt)
					$httpRange   = 0;
					$newFileSize = $fileSize - 1;
					// Default values! Will be overridden if a valid range header field was detected!
					$resultLenght = (string)$fileSize;
					$resultRange  = "0-".$newFileSize;
					// We support requests for a single range only.
					// So we check if we have a range field. If yes ensure that it is a valid one.
					// If it is not valid we ignore it and sending the whole file.
					if(isset($_SERVER['HTTP_RANGE']) && preg_match('%^bytes=\d*\-\d*$%', $_SERVER['HTTP_RANGE'])) {
						// Let's take the right side
						list($a, $httpRange) = explode('=', $_SERVER['HTTP_RANGE']);
						// and get the two values (as strings!)
						$httpRange = explode('-', $httpRange);
						// Check if we have values! If not we have nothing to do!
						if(!empty($httpRange[0]) || !empty($httpRange[1])) {
							// We need the new content length ...
							$resultLenght	= $fileSize - $httpRange[0] - $httpRange[1];
							// ... and we can add the 206 Status.
							header("HTTP/1.1 206 Partial Content");
							// Now we need the content-range, so we have to build it depending on the given range!
							// ex.: -500 -> the last 500 bytes
							if(empty($httpRange[0]))
								$resultRange = $resultLenght.'-'.$newFileSize;
							// ex.: 500- -> from 500 bytes to filesize
							elseif(empty($httpRange[1]))
								$resultRange = $httpRange[0].'-'.$newFileSize;
							// ex.: 500-1000 -> from 500 to 1000 bytes
							else
								$resultRange = $httpRange[0] . '-' . $httpRange[1];
							//header("Content-Range: bytes ".$httpRange . $newFileSize .'/'. $fileSize);
						} 
					}
					header("Content-Length: ". $resultLenght);
					header("Content-Range: bytes " . $resultRange . '/' . $fileSize);
					header("Content-Type: " . (string)$mimeType);
					header('Content-Disposition: attachment; filename="'.$fileWithoutPath.'"');
					header("Content-Transfer-Encoding: binary\n");
					
					// TEST TEMP SOLUTION - makes problems on somve server, @ added to prevent from warning
					// Do problems on some servers
					//@ob_end_clean();
					
					//@readfile($absOrRelFile);
					
					// Try to deliver in chunks
					@set_time_limit(0);
					$fp = @fopen($absOrRelFile, 'rb');
					if ($fp !== false) {
						while (!feof($fp)) {
							echo fread($fp, 8192);
						}
						fclose($fp);
					} else {
						@readfile($absOrRelFile);
					}
					flush();
					exit;
					
					/*
					https://www.phoca.cz/forum/viewtopic.php?f=31&t=11811
					
					$fp = @fopen($absOrRelFile, 'rb');
					// HTTP Range - see RFC2616 for more informations (http://www.ietf.org/rfc/rfc2616.txt)
					$newFileSize = $fileSize - 1;
					// Default values! Will be overridden if a valid range header field was detected!
					$rangeStart = 0;
					$rangeEnd = 0;
					$resultLength = $fileSize;
					// We support requests for a single range only.
					// So we check if we have a range field. If yes ensure that it is a valid one.
					// If it is not valid we ignore it and sending the whole file.
					if ($fp && isset($_SERVER['HTTP_RANGE']) && preg_match('%^bytes=\d*\-\d*$%', $_SERVER['HTTP_RANGE'])) {
						// Let's take the right side
						list($a, $httpRange) = explode('=', $_SERVER['HTTP_RANGE']);
						// and get the two values (as strings!)
						$httpRange = explode('-', $httpRange);
						// Check if we have values! If not we have nothing to do!
						if (sizeof($httpRange) == 2) {
							// Explictly convert to int
							$rangeStart = intval($httpRange[0]);
							$rangeEnd = intval($httpRange[1]); // Allowed to be empty == 0
							if (($rangeStart || $rangeEnd) // something actually set?
							&& $rangeStart < $fileSize // must be smaller
							&& $rangeEnd < $fileSize // must be smaller
							&& (!$rangeEnd || $rangeEnd > $rangeStart) // end > start, if end is set
							) {
								header("HTTP/1.1 206 Partial Content");
								if (!$rangeEnd) {
									$resultLength = $fileSize - $rangeStart;
									$range = $rangeStart . "-" . ($fileSize - 1) . "/" . $fileSize;
								} else {
									$resultLength = ($rangeEnd - $rangeStart 1);
									$range = $rangeStart . "-" . $rangeEnd . "/" . $fileSize;
								}
								header("Content-Range: bytes " . $range);
							} else {
								// Didn't validate: kill
								$rangeStart = 0;
								$rangeEnd = 0;
							}
						}
					}

					header("Content-Length: ". $resultLength);
					header("Content-Type: " . (string)$mimeType);
					header('Content-Disposition: attachment; filename="'.$fileWithoutPath.'"');
					header("Content-Transfer-Encoding: binary\n");
					@@ -211,13 +198,25 @@ class PhocaDownloadAccessFront

					// Try to deliver in chunks
					@set_time_limit(0);
					if ($fp !== false) {
						if ($rangeStart) {
							// Need to pass only part of the file, starting at $rangeStart
							fseek($fp, $rangeStart, SEEK_SET);
						}
						// If $rangeEnd is open ended (0, whole file from $rangeStart) try fpassthru,
						// else send in small chunks
						if ($rangeEnd || @!fpassthru($fp)) {
							while ($resultLength > 0 && !feof($fp)) {
								// 4 * 1460 (default MSS with ethernet 1500 MTU)
								// This is optimized for network packets, not disk access
								$bytes = min(5840, $resultLength);
								echo fread($fp, $bytes);
								$resultLength = $resultLength - $bytes;
							}
						}
						fclose($fp);
					} else {
						// Ranges are disabled at this point and were never set up
						@readfile($absOrRelFile);
					}
					flush();
					exit;
					*/
				}
			}
			
		}
		return false;
	
	}
	
	public static function getDownloadData($id, $return, $type = 0) {
	
		$outcome	= array();
		$wheres		= array();
		$db			= JFactory::getDBO();
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user		= JFactory::getUser();
		$redirectUrl= urlencode(base64_encode($return)); 
		$returnUrl  = 'index.php?option=com_users&view=login&return='.$redirectUrl;
		
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());
		
		$limitEnabled	= $params->get( 'user_files_max_count_download', 0 );
		if ((int)$limitEnabled > 0) {
			if ((int)$user->id < 1) {
				$app->redirect(JRoute::_($returnUrl, false), JText::_("COM_PHOCADOWNLOAD_NOT_LOGGED_IN_USERS_NOT_ALLOWED_DOWNLOAD"));
				exit;
			}
			$userFileCount = PhocaDownloadStat::getCountFilePerUser($id);
			(int)$userFileCount++;// Because we need to count this attempt too.
			if ((int)$userFileCount > (int)$limitEnabled) {
				$app->redirect(JRoute::_($returnUrl, false), JText::_("COM_PHOCADOWNLOAD_MAX_LIMIT_DOWNLOAD_PER_FILE_REACHED"));
				exit;
			}
		}
		
		
		
		
		$pQ				= $params->get( 'enable_plugin_query', 0 );
		
		$wheres[]	= " c.id = ".(int)$id;
		$wheres[] 	= " c.published = 1";
		$wheres[] 	= " c.approved 	= 1";
		$wheres[] 	= " c.catid = cc.id";
		
		if ($type == 1) {
			// Unique download link does not have any access
			$rightDisplay	= 1;
		
		} else {
			$wheres[]   = " cc.access IN (".$userLevels.")";
		}
		
		// Active
		$jnow		= JFactory::getDate();
		$now		= $jnow->toSql();
		$nullDate	= $db->getNullDate();
		$wheres[] 	= ' ( c.publish_up = '.$db->Quote($nullDate).' OR c.publish_up <= '.$db->Quote($now).' )';
		$wheres[] 	= ' ( c.publish_down = '.$db->Quote($nullDate).' OR c.publish_down >= '.$db->Quote($now).' )';
		
		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			JPluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = array();
			$results = \JFactory::getApplication()->triggerEvent('onGetDownload', array (&$wheres, &$joins,$id,  $paramsC));	
			// END GWE MOD
		}
		
		/*$query = " SELECT c.filename, c.directlink, c.access"
				." FROM #__phocadownload AS c"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY c.ordering";*/
		
		
		$query = ' SELECT c.id, c.catid, c.filename, c.directlink, c.link_external, c.access, c.confirm_license, c.metakey, c.metadesc, cc.access as cataccess, cc.accessuserid as cataccessuserid, c.tokenhits '
				.' FROM #__phocadownload AS c, #__phocadownload_categories AS cc '
				. ($pQ == 1 ? ((count($joins)>0?( ' LEFT JOIN ' .implode( ' LEFT JOIN ', $joins )):'')):'') // GWE MOD
				. ' WHERE ' . implode( ' AND ', $wheres )
				. ' ORDER BY c.ordering';

		$db->setQuery( $query , 0, 1 );	
		$filename = $db->loadObjectList();
		
		$limitTokenEnabled	= $params->get( 'token_files_max_count_download', 0 );
		if ((int)$limitTokenEnabled > 0) {
			if (isset($filename[0]->tokenhits)) {
				$tokenFileCount = $filename[0]->tokenhits;
				(int)$tokenFileCount++;// Because we need to count this attempt too.
				if ((int)$tokenFileCount > (int)$limitTokenEnabled) {
					$app->redirect(JRoute::_(htmlspecialchars($return)), JText::_("COM_PHOCADOWNLOAD_MAX_LIMIT_DOWNLOAD_TOKEN_REACHED"));
					exit;
				}
			}
		}
		
		
		//OSE Modified Start;
        if (!empty($filename[0])) {
			phocadownloadimport('phocadownload.utils.external');
			PhocaDownloadExternal::checkOSE($filename[0]);
        }
        //OSE Modified End; 
		

		// - - - - - - - - - - - - - - -
		// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
		// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
		
		$rightDisplay	= 0;
		if ($type == 1) {
			// Unique download link does not have any access
			$rightDisplay	= 1;
		
		} else {
			
			if (!empty($filename[0])) {
				$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $filename[0]->cataccessuserid, $filename[0]->cataccess, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
			}
			// - - - - - - - - - - - - - - - - - - - - - -
			if ($rightDisplay == 0) {
				$app->redirect(JRoute::_($returnUrl, false), JText::_("COM_PHOCADOWNLOAD_NO_RIGHTS_ACCESS_CATEGORY_FILE"));
				exit;
			}
		
		}
		
		
		
		
		
		if (empty($filename)) {
			$outcome['file'] 			= "COM_PHOCADOWNLOAD_ERROR_NO_DB_RESULT";
			$outcome['directlink']		= 0;
			$outcome['externallink']	= 0;
			return $outcome;
		} 
		
		if ($type == 1) {
			// Unique download link
		} else {
			if (isset($filename[0]->access)) {
				if (!in_array($filename[0]->access, $user->getAuthorisedViewLevels())) {
					$app->redirect(JRoute::_($returnUrl, false), JText::_('COM_PHOCADOWNLOAD_PLEASE_LOGIN_DOWNLOAD_FILE'));
					exit;
				}
			} else {
				$outcome['file'] 			= "COM_PHOCADOWNLOAD_ERROR_NO_DB_RESULT";
				$outcome['directlink']		= 0;
				$outcome['externallink']	= 0;
				return $outcome;
			}
		}
		// - - - - - - - - - - - - - - - -
		
		
		$filenameT 		= $filename[0]->filename;
		$directlinkT 	= $filename[0]->directlink;
		$linkExternalT 	= $filename[0]->link_external;
		
		// Unique Download Link
		if ($type == 1) {
			$directlinkT = 0;// Unique Download Link cannot work with direct link
		}
		
		$filePath				= PhocaDownloadPath::getPathSet('file');
		
		if ($filenameT !='') {
			
			// Important - you cannot use direct link if you have selected absolute path
			// Absolute Path defined by user
			$absolutePath	= $params->get( 'absolute_path', '' );
			if ($absolutePath != '') {
				$directlinkT = 0;
			}
			
			if ($directlinkT == 1 ) {
				$relFile = JURI::base(true).'/'.$params->get('download_folder', 'phocadownload' ).'/'.$filenameT;
				$outcome['file'] 		= $relFile;
				$outcome['directlink']	= $directlinkT;
				$outcome['externallink']= $linkExternalT;
				return $outcome;
			} else if ($directlinkT == 0 && $linkExternalT != '' ) {
				$relFile = JURI::base(true).'/'.$params->get('download_folder', 'phocadownload' ).'/'.$filenameT;
				$outcome['file'] 		= $relFile;
				$outcome['directlink']	= $directlinkT;
				$outcome['externallink']= $linkExternalT;
				return $outcome;
			} else {
				$absFile = str_replace('\\', '/', JPath::clean($filePath['orig_abs_ds'] . $filenameT));
			}
	
			if (JFile::exists($absFile)) {
				$outcome['file'] 		= $absFile;
				$outcome['directlink']	= $directlinkT;
				$outcome['externallink']= $linkExternalT;
				return $outcome;
			} else {
			
				$outcome['file'] 		= "COM_PHOCADOWNLOAD_ERROR_NO_ABS_FILE";
				$outcome['directlink']	= 0;
				$outcome['externallink']= $linkExternalT;
				return $outcome;
			}
		} else {
		
				$outcome['file'] 		= "COM_PHOCADOWNLOAD_ERROR_NO_DB_FILE";
				$outcome['directlink']	= 0;
				$outcome['externallink']= $linkExternalT;
				return $outcome;
		}
	}
	
	protected static function hit($id) {
		
		$app	= JFactory::getApplication();
		$table 	= JTable::getInstance('PhocaDownload', 'Table');
		$table->hit($id);
		return true;
	}
	
	protected static function hitToken($id) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->update('#__phocadownload')
			->set($db->quoteName('tokenhits') . ' = (' . $db->quoteName('tokenhits') . ' + 1)')
			->where('id = ' . $db->quote((int)$id));
		$db->setQuery($query);
		
		$db->execute();
		return true;
	}
}
?>