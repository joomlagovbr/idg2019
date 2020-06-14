<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access'); 

$l = new PhocaDownloadLayout();

if (!empty($this->files)) {	
	foreach ($this->files as $v) {
		
		
	
		if ($this->checkRights == 1) {
			// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
			// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
			$rightDisplay	= 0;
			
			if (!isset($v->cataccessuserid)) {
				$v->cataccessuserid = 0;
			}
			if (isset($v->catid) && isset($v->cataccessuserid) && isset($v->cataccess)) {
				$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $v->cataccessuserid, $v->cataccess, $this->t['user']->getAuthorisedViewLevels(), $this->t['user']->get('id', 0), 0);
			}
			// - - - - - - - - - - - - - - - - - - - - - -
		} else {
			$rightDisplay = 1;
		}
		
		if ($rightDisplay == 1) {
		
			// Test if we have information about category - if we are displaying items by e.g. search outcomes - tags
			// we don't have any ID of category so we need to load it for each file.
			$this->catitem[$v->id]			= new StdClass();
			$this->catitem[$v->id]->id 		= 0;
			$this->catitem[$v->id]->alias 	= '';
			
			if (isset($this->category[0]->id) && isset($this->category[0]->alias)) {
				$this->catitem[$v->id]->id 		= (int)$this->category[0]->id;
				$this->catitem[$v->id]->alias 	= $this->category[0]->alias;
			} else {
				$catDb = PhocaDownloadCategory::getCategoryByFile($v->id);
				if (isset($catDb->id) && isset($catDb->alias)) {
					$this->catitem[$v->id]->id 		= (int)$catDb->id;
					$this->catitem[$v->id]->alias 	= $catDb->alias;	
				}
				$categorySetTemp = 1;
				
			}
			
			$cBtnDanger 	= 'btn btn-danger';
			$cBtnWarning 	= 'btn btn-warning';
			$cBtnSuccess	= 'btn btn-success';
			$cBtnInfo		= 'btn btn-info';
			
			/*$cBtnDanger 	= '';
			$cBtnWarning 	= '';
			$cBtnSuccess	= '';
			$cBtnInfo		= '';*/
		
			// General
			$linkDownloadB = '';
			$linkDownloadE = '';
			if ((int)$v->confirm_license > 0 || $this->t['display_file_view'] == 1) {
				$linkDownloadB = '<a class="" href="'. JRoute::_(PhocaDownloadRoute::getFileRoute($v->id, $v->catid,$v->alias, $v->categoryalias, $v->sectionid). $this->t['limitstarturl']).'" >';	// we need pagination to go back			
				$linkDownloadE ='</a>';
			} else {
				if ($v->link_external != '' && $v->directlink == 1) {
					$linkDownloadB = '<a class="" href="'.$v->link_external.'" target="'.$this->t['download_external_link'].'" >';
					$linkDownloadE ='</a>';
				} else {
					$linkDownloadB = '<a class="" href="'. JRoute::_(PhocaDownloadRoute::getFileRoute($v->id, $this->catitem[$v->id]->id,$v->alias, $this->catitem[$v->id]->alias, $v->sectionid, 'download').$this->t['limitstarturl']).'" >';
					$linkDownloadE ='</a>';
				}
			}
			
			// pdtextonly
			$pdTextOnly = '<div class="pd-textonly">'.$v->description.'</div>' . "\n";
			
			// pdfile
			if ($v->filename != '') {
				$imageFileName = $l->getImageFileName($v->image_filename, $v->filename);
				
				$pdFile = '<div class="pd-filenamebox">';
				if ($this->t['filename_or_name'] == 'filenametitle') {
					$pdFile .= '<div class="pd-title">'. $v->title . '</div>';
				}
				
				$pdFile .= '<div class="pd-filename">'. $imageFileName['filenamethumb']
					. '<div class="pd-document'.$this->t['file_icon_size'].'" '
					. $imageFileName['filenamestyle'].'>';
				
				$pdFile .= '<div class="pd-float">';
				$pdFile .= $linkDownloadB .$l->getName($v->title, $v->filename) .$linkDownloadE;
				$pdFile .= '</div>';
				
				$pdFile .= PhocaDownloadRenderFront::displayNewIcon($v->date, $this->t['displaynew']);
				$pdFile .= PhocaDownloadRenderFront::displayHotIcon($v->hits, $this->t['displayhot']);
				
				// String Tags - title suffix
				$tagsS = $l->displayTagsString($v->tags_string);
				if ($tagsS != '') {
					$pdFile .= '<div class="pd-float">'.$tagsS.'</div>';
				}
				
				// Tags - title suffix
				if ($this->t['display_tags_links'] == 4 || $this->t['display_tags_links'] == 6) {
					$tags = $l->displayTags($v->id, 1);
					if ($tags != '') {
						$pdFile .= '<div class="pd-float">'.$tags.'</div>';
					}
				}
				
				//Specific icons
				if (isset($v->image_filename_spec1) && $v->image_filename_spec1 != '') {
					$pdFile .= '<div class="pd-float">'.$l->getImageDownload($v->image_filename_spec1).'</div>';
				} 
				if (isset($v->image_filename_spec2) && $v->image_filename_spec2 != '') {
					$pdFile .= '<div class="pd-float">'.$l->getImageDownload($v->image_filename_spec2).'</div>';
				} 
				
				$pdFile .= '</div></div></div>' . "\n";
			}
			
			
			
			
			
			// pdbuttonplay
			$pdButtonPlay = '';
			if (isset($v->filename_play) && $v->filename_play != '') {
				$fileExt 	= PhocaDownloadFile::getExtension($v->filename_play);
				$canPlay	= PhocaDownloadFile::canPlay($v->filename_play);
				
				if ($canPlay) {
					// Special height for music only
					$buttonPlOptions = $this->t['buttonpl']->options;
					if ($fileExt == 'mp3' || $fileExt == 'ogg') {
						$buttonPlOptions = $this->t['buttonpl']->optionsmp3;
					}
					$playLink = JRoute::_(PhocaDownloadRoute::getFileRoute($v->id,$v->catid,$v->alias, $v->categoryalias,0, 'play').$this->t['limitstarturl']);
					//class="btn btn-danger"
					$pdButtonPlay .= '<div class="pd-button-play">';
					if ($this->t['play_popup_window'] == 1) {
						$pdButtonPlay .= '<a class="'.$cBtnDanger.'" href="'.$playLink.'" onclick="'. $buttonPlOptions.'" >'. JText::_('COM_PHOCADOWNLOAD_PLAY').'</a>';
					} else {	
						$pdButtonPlay .= '<a class="'.$cBtnDanger.' pd-modal-button" href="'.$playLink.'" rel="'. $buttonPlOptions.'" >'. JText::_('COM_PHOCADOWNLOAD_PLAY').'</a>';
					}
					$pdButtonPlay .= '</div>';
				}
			}
			
			// pdbuttonpreview
			$pdButtonPreview = '';
			if (isset($v->filename_preview) && $v->filename_preview != '') {
				$fileExt = PhocaDownloadFile::getExtension($v->filename_preview);
				if ($fileExt == 'pdf' || $fileExt == 'jpeg' || $fileExt == 'jpg' || $fileExt == 'png' || $fileExt == 'gif') {
		
					$filePath	= PhocaDownloadPath::getPathSet('filepreview');
					$filePath	= str_replace ( '../', JURI::base(true).'/', $filePath['orig_rel_ds']);
					$previewLink = $filePath . $v->filename_preview;	
					$pdButtonPreview	.= '<div class="pd-button-preview">';
					
					if ($this->t['preview_popup_window'] == 1) {
						$pdButtonPreview .= '<a class="'.$cBtnWarning.'" href="'.$previewLink.'" onclick="'. $this->t['buttonpr']->options.'" >'. JText::_('COM_PHOCADOWNLOAD_PREVIEW').'</a>';
					} else {	
						if ($fileExt == 'pdf') {
							// Iframe - modal
							$pdButtonPreview .= '<a class="'.$cBtnWarning.' pd-modal-button" href="'.$previewLink.'" rel="'. $this->t['buttonpr']->options.'" >'. JText::_('COM_PHOCADOWNLOAD_PREVIEW').'</a>';
						} else {
							// Image - modal
							$pdButtonPreview .= '<a class="'.$cBtnWarning.' pd-modal-button" href="'.$previewLink.'" rel="'. $this->t['buttonpr']->optionsimg.'" >'. JText::_('COM_PHOCADOWNLOAD_PREVIEW').'</a>';
						}
					}
					$pdButtonPreview	.= '</div>';
				}
			}
			
			// pdbuttondownload
			$pdButtonDownload = '<div class="pd-button-download">';
			$pdButtonDownload .= str_replace('class=""', 'class="'.$cBtnSuccess.'"', $linkDownloadB) . JText::_('COM_PHOCADOWNLOAD_DOWNLOAD') .$linkDownloadE;
			
			$pdButtonDownload .= '</div>';
			
			
			
			// pdbuttondetails
			$d = '';
			
			$pdTitle = '';
			if ($v->title != '') {
				$pdTitle .= '<div class="pd-title">'.$v->title.'</div>';
				$d .= $pdTitle;
			}
			
			$pdImage = '';
			if ($v->image_download != '') {
				$pdImage .= '<div class="pd-image">'.$l->getImageDownload($v->image_download).'</div>';
				$d .= $pdImage;			
			}
			
			$pdFileSize = '';
			$fileSize = $l->getFilesize($v->filename);
			if ($fileSize != '') {
				$pdFileSize .= '<div class="pd-filesize-txt">'.JText::_('COM_PHOCADOWNLOAD_FILESIZE').':</div>';
				$pdFileSize .= '<div class="pd-fl-m">'.$fileSize.'</div>';
				$d .= $pdFileSize;
			}
				
			$pdVersion = '';
			if ($v->version != '') {
				$pdVersion .= '<div class="pd-version-txt">'.JText::_('COM_PHOCADOWNLOAD_VERSION').':</div>';
				$pdVersion .= '<div class="pd-fl-m">'.$v->version.'</div>';
				$d .= $pdVersion;
			}
			
			$pdLicense = '';
			if ($v->license != '') {
				if ($v->license_url != '') {
					$pdLicense .= '<div class="pd-license-txt">'.JText::_('COM_PHOCADOWNLOAD_LICENSE').':</div>';
					$pdLicense .= '<div class="pd-fl-m"><a href="'.$v->license_url.'" target="_blank">'.$v->license.'</a></div>';
				} else {
					$pdLicense .= '<div class="pd-license-txt">'.JText::_('COM_PHOCADOWNLOAD_LICENSE').':</div>';
					$pdLicense .= '<div class="pd-fl-m">'.$v->license.'</div>';
				}
				$d .= $pdLicense;
			}
			
			$pdAuthor = '';
			if ($v->author != '') {
				if ($v->author_url != '') {
					$pdAuthor .= '<div class="pd-author-txt">'.JText::_('COM_PHOCADOWNLOAD_AUTHOR').':</div>';
					$pdAuthor .= '<div class="pd-fl-m"><a href="'.$v->author_url.'" target="_blank">'.$v->author.'</a></div>';
				} else {
					$pdAuthor .= '<div class="pd-author-txt">'.JText::_('COM_PHOCADOWNLOAD_AUTHOR').':</div>';
					$pdAuthor .= '<div class="pd-fl-m">'.$v->author.'</div>';
				}
				$d .= $pdAuthor;
			}
			
			$pdAuthorEmail = '';
			if ($v->author_email != '') {
				$pdAuthorEmail .= '<div class="pd-email-txt">'.JText::_('COM_PHOCADOWNLOAD_EMAIL').':</div>';
				$pdAuthorEmail .= '<div class="pd-fl-m">'. $l->getProtectEmail($v->author_email).'</div>';
				$d .= $pdAuthorEmail;
			}
			
			$pdFileDate = '';
			$fileDate = $l->getFileDate($v->filename, $v->date);
			if ($fileDate != '') {
				$pdFileDate .= '<div class="pd-date-txt">'.JText::_('COM_PHOCADOWNLOAD_DATE').':</div>';
				$pdFileDate .= '<div class="pd-fl-m">'.$fileDate.'</div>';
				$d .= $pdFileDate;
			}
				
			$pdDownloads = '';
			if ($this->t['display_downloads'] == 1) {
				$pdDownloads .= '<div class="pd-downloads-txt">'.JText::_('COM_PHOCADOWNLOAD_DOWNLOADS').':</div>';
				$pdDownloads .= '<div class="pd-fl-m">'.$v->hits.' x</div>';
				$d .= $pdDownloads;
			}
			
			$pdDescription = '';
			if ($l->isValueEditor($v->description) && $this->t['display_description'] != 1 & $this->t['display_description'] != 2 & $this->t['display_description'] != 3) {
				$pdDescription .= '<div class="pd-fdesc">'.$v->description.'</div>';
				$d .= $pdDescription;
			}
			
			$pdFeatures = '';
			if ($l->isValueEditor($v->features)) {
				$pdFeatures .= '<div class="pd-features-txt">'.JText::_('COM_PHOCADOWNLOAD_FEATURES').'</div>';
				$pdFeatures .= '<div class="pd-features">'.$v->features.'</div>';
			}
			
			$pdChangelog = '';
			if ($l->isValueEditor($v->changelog)) {
				$pdChangelog .= '<div class="pd-changelog-txt">'.JText::_('COM_PHOCADOWNLOAD_CHANGELOG').'</div>';
				$pdChangelog .= '<div class="pd-changelog">'.$v->changelog.'</div>';
			}
			
			$pdNotes = '';
			if ($l->isValueEditor($v->notes)) {
				$pdNotes .= '<div class="pd-notes-txt">'.JText::_('COM_PHOCADOWNLOAD_NOTES').'</div>';
				$pdNotes .= '<div class="pd-notes">'.$v->notes.'</div>';
			}

			
			// pdfiledesc
			$description = $l->isValueEditor($v->description);
			
			$pdFileDescTop 		= '';
			$pdFileDescBottom	= '';
			$oFileDesc			= '';
			
			if ($description) {
				switch($this->t['display_description']) {
					
					case 1:
						$pdFileDescTop		= '<div class="pd-fdesc">'.$v->description.'</div>';
					break;
					case 2:
						$pdFileDescBottom	= '<div class="pd-fdesc">'.$v->description.'</div>';
					break;
					case 3:
						$oFileDesc			= '<div class="pd-fdesc">'.$v->description.'</div>';
					break;
					case 4:
						$pdFileDescTop		= '<div class="pd-fdesc">'.$v->description.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadUtils::strTrimAll($d).'</div>';
					break;
					case 5:
						$pdFileDescBottom	= '<div class="pd-fdesc">'.$v->description.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadUtils::strTrimAll($d).'</div>';
					break;
					case 6:
						$pdFileDescTop		= '<div class="pd-fdesc">'.$d.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadUtils::strTrimAll($d).'</div>';
					break;
					case 7:
						$pdFileDescBottom	= '<div class="pd-fdesc">'.$d.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadUtils::strTrimAll($d).'</div>';
					break;
					
					case 8:
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadUtils::strTrimAll($d).'</div>';
					break;
					
					default:
					break;
				}
			}
			
			// Detail Button
			if ($this->t['display_detail'] == 1) {
				if ($oFileDesc	!= '') {
					$overlibcontent = $oFileDesc;
				} else {
					$overlibcontent = $d;
				}
				
				$overlibcontent = str_replace('"', '\'', $overlibcontent);
				$sA = array(utf8_encode(chr(11)), utf8_encode(chr(160)));
				$eA	= array("\t", "\n", "\r", "\0");
				$overlibcontent = str_replace($sA, ' ', $overlibcontent);
				$overlibcontent = str_replace($eA, '', $overlibcontent);

				$textO = htmlspecialchars(addslashes('<div style=\'text-align:left;padding:5px\'>'.$overlibcontent.'</div>'));
				$overlib 	= "\n\n" ."onmouseover=\"return overlib('".$textO."', CAPTION, '".JText::_('COM_PHOCADOWNLOAD_DETAILS')."', BELOW, RIGHT, CSSCLASS, TEXTFONTCLASS, 'fontPhocaPDClass', FGCLASS, 'fgPhocaPDClass', BGCLASS, 'bgPhocaPDClass', CAPTIONFONTCLASS,'capfontPhocaPDClass', CLOSEFONTCLASS, 'capfontclosePhocaPDClass', STICKY, MOUSEOFF, CLOSETEXT, '".JText::_('COM_PHOCADOWNLOAD_CLOSE')."');\"";
				$overlib .= " onmouseout=\"return nd();\"" . "\n";
			
		
				$pdButtonDetails = '<div class="pd-button-details">';
				$pdButtonDetails .= '<a class="'.$cBtnInfo.'" '.$overlib.' href="#">'. JText::_('COM_PHOCADOWNLOAD_DETAILS').'</a>';
				$pdButtonDetails .= '</div>';
			} else if ($this->t['display_detail'] == 2) {
				$buttonDOptions = $this->t['buttond']->options;
				$detailLink 	= JRoute::_(PhocaDownloadRoute::getFileRoute($v->id, $this->catitem[$v->id]->id,$v->alias, $this->catitem[$v->id]->alias, 0, 'detail').$this->t['limitstarturl']);
				$pdButtonDetails = '<div class="pd-button-details">';
				$pdButtonDetails .= '<a class="'.$cBtnInfo.' pd-modal-button" href="'.$detailLink.'" rel="'. $buttonDOptions.'">'. JText::_('COM_PHOCADOWNLOAD_DETAILS').'</a>';
				
				$pdButtonDetails .= '</div>';
				
			} else if ($this->t['display_detail'] == 3) {
				$buttonDOptions = $this->t['buttond']->options;
				$detailLink 	= JRoute::_(PhocaDownloadRoute::getFileRoute($v->id, $this->catitem[$v->id]->id,$v->alias, $this->catitem[$v->id]->alias, 0, 'detail').$this->t['limitstarturl']);
				$pdButtonDetails = '<div class="pd-button-details">';
				$pdButtonDetails .= '<a class="'.$cBtnInfo.' pd-modal-button" href="'.$detailLink.'" onclick="'. $buttonDOptions.'">'. JText::_('COM_PHOCADOWNLOAD_DETAILS').'</a>';
				
				$pdButtonDetails .= '</div>';
				
			} else {
				$pdButtonDetails = '';
			}
			
			
			// pdmirrorlink1
			$pdMirrorLink1 = '';
			$mirrorOutput1 = PhocaDownloadRenderFront::displayMirrorLinks(1, $v->mirror1link, $v->mirror1title, $v->mirror1target);
			if ($mirrorOutput1 != '') {
				
				if ($this->t['display_mirror_links'] == 4 || $this->t['display_mirror_links'] == 6) {
					$classMirror = 'pd-button-mirror1';
					$mirrorOutput1 = str_replace('class=""', 'class="btn"', $mirrorOutput1);
				} else {
					$classMirror = 'pd-mirror';
				}
				
				$pdMirrorLink1 = '<div class="'.$classMirror.'">'.$mirrorOutput1.'</div>';
			}

			// pdmirrorlink2
			$pdMirrorLink2 = '';
			$mirrorOutput2 = PhocaDownloadRenderFront::displayMirrorLinks(1, $v->mirror2link, $v->mirror2title, $v->mirror2target);
			if ($mirrorOutput2 != '') {
				if ($this->t['display_mirror_links'] == 4 || $this->t['display_mirror_links'] == 6) {
					$classMirror = 'pd-button-mirror2';
					$mirrorOutput2 = str_replace('class=""', 'class="btn"', $mirrorOutput2);
				} else {
					$classMirror = 'pd-mirror';
				}
			
				$pdMirrorLink2 = '<div class="'.$classMirror.'">'.$mirrorOutput2.'</div>';
			}
			
			// pdreportlink
			$pdReportLink = PhocaDownloadRenderFront::displayReportLink(1, $v->title);

			
			// pdrating
			$pdRating 	= PhocaDownloadRate::renderRateFile($v->id, $this->t['display_rating_file']);
			
			// pdtags
			$pdTags = '';
			if ($this->t['display_tags_links'] == 1 || $this->t['display_tags_links'] == 3) {
				$tags2 = $l->displayTags($v->id);
				if ($tags2 != '') {
					$pdTags .= '<div class="pd-float">'.$tags2.'</div>';
				}
			}
			
			//pdvideo
			$pdVideo = $l->displayVideo($v->video_filename, 0);
			
			
			// ---------------------------------------------------
			//Convert
			// ---------------------------------------------------
			if ($v->textonly == 1) {
				echo '<div class="pd-textonly">'. $pdTextOnly . '</div>';
			} else {

				if ($this->t['display_specific_layout'] == 0) {
					echo '<div class="pd-filebox">';
					echo $pdFileDescTop;
					echo $pdFile;
					echo '<div class="pd-buttons">'.$pdButtonDownload.'</div>';
					
					if ($this->t['display_detail'] == 1 || $this->t['display_detail'] == 2) {
						echo '<div class="pd-buttons">'.$pdButtonDetails.'</div>';
					}
					
					if ($this->t['display_preview'] == 1 && $pdButtonPreview != '') {
						echo '<div class="pd-buttons">'.$pdButtonPreview.'</div>';
					}
					
					if ($this->t['display_play'] == 1 && $pdButtonPlay != '') {
						echo '<div class="pd-buttons">'.$pdButtonPlay.'</div>';
					}
					
					if ($this->t['display_mirror_links'] == 4 || $this->t['display_mirror_links'] == 6) {
						if ($pdMirrorLink2 != '') {
							echo '<div class="pd-buttons">'.$pdMirrorLink2.'</div>';
						}
						if ($pdMirrorLink1 != '') {
							echo '<div class="pd-buttons">'.$pdMirrorLink1.'</div>';
						}

					} else if ($this->t['display_mirror_links'] == 1 || $this->t['display_mirror_links'] == 3) {
						echo '<div class="pd-mirrors">'.$pdMirrorLink2.$pdMirrorLink1.'</div>';
					}
					
					if ($pdVideo != '') {
						echo '<div class="pd-video">'.$pdVideo.'</div>';
					}
					
					if ($pdReportLink != '') {
						echo '<div class="pd-report">'.$pdReportLink.'</div>';
					}
					
					if ($pdRating != '') {
						echo '<div class="pd-rating">'.$pdRating.'</div>';
					}
					
					if ($pdTags != '') {
						echo '<div class="pd-tags">'.$pdTags.'</div>';
					}
					echo $pdFileDescBottom;
					echo '<div class="pd-cb"></div>';
					echo '</div>';
				
				} else {
				
				/*$categoryLayout = '<div class="pd-filebox">
				{pdfiledesctop}
				{pdfile}
				<div class="pd-buttons">{pdbuttondownload}</div>
				<div class="pd-buttons">{pdbuttondetails}</div>
				<div class="pd-buttons">{pdbuttonpreview}</div>
				<div class="pd-buttons">{pdbuttonplay}</div>
				<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
				<div class="pd-rating">{pdrating}</div>
				<div class="pd-tags">{pdtags}</div>
				{pdfiledescbottom}
				<div class="pd-cb"></div>
				</div>';*/
				
					$categoryLayout 		= PhocaDownloadSettings::getLayoutText('category');
					$categoryLayoutParams 	= PhocaDownloadSettings::getLayoutParams('category');
						
					$replace	= array($pdTitle, $pdImage, $pdFile, $pdFileSize, $pdVersion, $pdLicense, $pdAuthor, $pdAuthorEmail, $pdFileDate, $pdDownloads, $pdDescription, $pdFeatures, $pdChangelog, $pdNotes, $pdMirrorLink1, $pdMirrorLink2, $pdReportLink, $pdRating, $pdTags, $pdFileDescTop, $pdFileDescBottom, $pdButtonDownload, $pdButtonDetails, $pdButtonPreview, $pdButtonPlay, $pdVideo );
					$output		= str_replace($categoryLayoutParams['search'], $replace, $categoryLayout);
					
					echo $output;
					
					
			// ---------------------------------------------------	
			}
		
				}
		}
	}
}
?>
