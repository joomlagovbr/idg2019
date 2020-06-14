<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

echo '<div id="phoca-dl-file-box" class="pd-file-view'.$this->t['p']->get( 'pageclass_sfx' ).'" >';

if ( $this->t['p']->get( 'show_page_heading' ) ) {
	echo '<h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1>';
}

if (!empty($this->category[0])) {
	echo '<div class="pd-file">';
	if ($this->t['display_up_icon'] == 1 && $this->t['tmplr'] == 0) {

		if (isset($this->category[0]->id)) {
			if ($this->category[0]->id > 0) {
				$linkUp = JRoute::_(PhocaDownloadRoute::getCategoryRoute($this->category[0]->id, $this->category[0]->alias));
				$linkUpText = $this->category[0]->title;
			} else {
				$linkUp 	= '#';
				$linkUpText = '';
			}

			echo '<div class="ph-top">'
				.'<a class="btn btn-default" title="'.$linkUpText.'" href="'. $linkUp.'" ><span class="glyphicon glyphicon-arrow-left"></span> '
				. $linkUpText
				.'</a></div>';
		}
	}
} else {
	echo '<div class="pd-file"><div class="ph-top"></div>';
}


if (!empty($this->file[0])) {
	$v = $this->file[0];

	// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
	// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
	$rightDisplay	= 0;
	if (!empty($this->category[0])) {
		$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $v->cataccessuserid, $v->cataccess, $this->t['user']->getAuthorisedViewLevels(), $this->t['user']->get('id', 0), 0);
	}
	// - - - - - - - - - - - - - - - - - - - - - -

	if ($rightDisplay == 1) {

		$l = new PhocaDownloadLayout();

		echo '<h3 class="pdfv-name">'.$l->getName($v->title, $v->filename, 1). '</h3>';


// =====================================================================================
// BEGIN LAYOUT AREA
// =====================================================================================

		// Is this direct menu link to File View
		$directFv 	= 0;
		$app		= JFactory::getApplication();
		$itemId 	= $app->input->get('Itemid', 0, 'int');
		$menu		= $app->getMenu();
		$item		= $menu->getItem($itemId);
		if (isset($item->query['view']) && $item->query['view'] == 'file') {
			$directFv = 1;
		}
		// End direct menu link to File View

		if ((int)$this->t['display_file_view'] == 1
		|| (int)$this->t['display_file_view'] == 2
		|| (int)$v->confirm_license > 0
		|| (int)$this->t['display_detail'] == 2
		|| (int)$this->t['display_detail'] == 3
		|| (int)$directFv == 1) {

			$pdTitle = '';
			if ($v->title != '' && $this->t['filename_or_name'] != 'filenametitle') {
				$pdTitle .= '<div class="pd-title">'.$v->title.'</div>';
			}

			$pdImage = '';
			if ($v->image_download != '') {
				$pdImage .= '<div class="pd-image">'.$l->getImageDownload($v->image_download).'</div>';
			}

			$pdVideo = '';
			$pdVideo = $l->displayVideo($v->video_filename, 1);

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
				$pdFile .= $l->getName($v->title, $v->filename);
				$pdFile .= '</div>';

				$pdFile .= PhocaDownloadRenderFront::displayNewIcon($v->date, $this->t['displaynew']);
				$pdFile .= PhocaDownloadRenderFront::displayHotIcon($v->hits, $this->t['displayhot']);

				// String Tags - title suffix
				$tagsS = $l->displayTagsString($v->tags_string);
				if ($tagsS != '') {
					$pdFile .= '<div class="pd-float">'.$tagsS.'</div>';
				}

				// Tags - title suffix
				if ($this->t['display_tags_links'] == 5 || $this->t['display_tags_links'] == 6) {
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

			$pdFileSize = '';
			$fileSize = $l->getFilesize($v->filename);
			if ($fileSize != '') {
				$pdFileSize .= '<div class="pd-filesize-txt">'.JText::_('COM_PHOCADOWNLOAD_FILESIZE').':</div>';
				$pdFileSize .= '<div class="pd-fl-m">'.$fileSize.'</div>';
			}

			$pdVersion = '';
			if ($v->version != '') {
				$pdVersion .= '<div class="pd-version-txt">'.JText::_('COM_PHOCADOWNLOAD_VERSION').':</div>';
				$pdVersion .= '<div class="pd-fl-m">'.$v->version.'</div>';
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
			}

			$pdAuthorEmail = '';
			if ($v->author_email != '') {
				$pdAuthorEmail .= '<div class="pd-email-txt">'.JText::_('COM_PHOCADOWNLOAD_EMAIL').':</div>';
				$pdAuthorEmail .= '<div class="pd-fl-m">'. $l->getProtectEmail($v->author_email).'</div>';
			}

			$pdFileDate = '';
			$fileDate = $l->getFileDate($v->filename, $v->date);
			if ($fileDate != '') {
				$pdFileDate .= '<div class="pd-date-txt">'.JText::_('COM_PHOCADOWNLOAD_DATE').':</div>';
				$pdFileDate .= '<div class="pd-fl-m">'.$fileDate.'</div>';
			}

			$pdDownloads = '';
			if ($this->t['display_downloads'] == 1) {
				$pdDownloads .= '<div class="pd-downloads-txt">'.JText::_('COM_PHOCADOWNLOAD_DOWNLOADS').':</div>';
				$pdDownloads .= '<div class="pd-fl-m">'.$v->hits.' x</div>';

				/*for ($i = 2; $i < 1001; $i++) {
					if (($v->hits > 1 && $v->hits < 5)
						|| ($v->hits > $i * 10 + 1 && $v->hits < $i * 10 + 5)) {
						$numD = 'COM_PHOCADOWNLOAD_NUMBER_OF_DOWNLOADS_3';
						break;
					} elseif (($v->hits == 0)
						|| ($v->hits > 4 && $v->hits < 22)
						|| ($v->hits > $i * 10 + 4 && $v->hits < $i * 10 + 12)) {
						$numD = 'COM_PHOCADOWNLOAD_NUMBER_OF_DOWNLOADS_2';
						break;
					} elseif ($v->hits == 1) {
						$numD = 'COM_PHOCADOWNLOAD_NUMBER_OF_DOWNLOADS_1';
						break;
					}
				}
				$pdDownloads .= '<div class="pd-fl-m">'.$v->hits.' '.JText::_($numD).'</div>';
				*/
			}

			$pdDescription = '';
			if ($l->isValueEditor($v->description)) {
				$pdDescription .= '<div class="pd-fdesc">'.$v->description.'</div>';
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


			/// pdmirrorlink1
			$pdMirrorLink1 = '';
			$mirrorOutput1 = PhocaDownloadRenderFront::displayMirrorLinks(1, $v->mirror1link, $v->mirror1title, $v->mirror1target);

			if ($mirrorOutput1 != '') {

				if ($this->t['display_mirror_links'] == 4 || $this->t['display_mirror_links'] == 6) {
					$classMirror = 'pd-button-mirror1';
					$mirrorOutput1 = str_replace('class=""', 'class="btn btn-primary "', $mirrorOutput1);
				} else {
					$classMirror = 'pd-mirror-bp';
				}

				$pdMirrorLink1 = '<div class="'.$classMirror.'">'.$mirrorOutput1.'</div>';
			}

			/// pdmirrorlink2
			$pdMirrorLink2 = '';
			$mirrorOutput2 = PhocaDownloadRenderFront::displayMirrorLinks(1, $v->mirror2link, $v->mirror2title, $v->mirror2target);
			if ($mirrorOutput2 != '') {
				if ($this->t['display_mirror_links'] == 4 || $this->t['display_mirror_links'] == 6) {
					$classMirror = 'pd-button-mirror2';
					$mirrorOutput2 = str_replace('class=""', 'class="btn btn-primary "', $mirrorOutput2);
				} else {
					$classMirror = 'pd-mirror-bp';
				}

				$pdMirrorLink2 = '<div class="'.$classMirror.'">'.$mirrorOutput2.'</div>';
			}

			// pdreportlink
			$pdReportLink = PhocaDownloadRenderFront::displayReportLink(1, $v->title);


			// pdrating
			$pdRating 	= PhocaDownloadRate::renderRateFile($v->id, $this->t['display_rating_file']);

			// pdtags
			$pdTags = '';
			if ($this->t['display_tags_links'] == 2 || $this->t['display_tags_links'] == 3) {
				$tags2 = $l->displayTags($v->id);
				if ($tags2 != '') {
					$pdTags .= '<div class="pd-float">'.$tags2.'</div>';
				}
			}

			// RENDER
			echo '<div class="pd-filebox">';
			echo '<div class="row ">';
			echo '<div class="col-sm-12 col-md-12">';
			echo $pdTitle;
			echo $pdImage;
			echo $pdFile;
			echo $pdFileSize;
			echo $pdVersion;
			echo $pdLicense;
			echo $pdAuthor;
			echo $pdAuthorEmail;
			echo $pdFileDate;
			echo $pdDownloads;
			echo $pdDescription;
			echo $pdFeatures;
			echo $pdChangelog;
			echo $pdNotes;
			echo '<div class="pd-video">'.$pdVideo.'</div>';
			echo '<div class="pd-rating">'.$pdRating.'</div>';
			echo '</div></div>'; // end col, end row


			echo '<div class="row ">';
			echo '<div class="col-sm-12 col-md-12">';
			if ($this->t['display_mirror_links'] == 5 || $this->t['display_mirror_links'] == 6) {
				echo '<div class="pd-buttons-bp">'.$pdMirrorLink2.'</div>';
				echo '<div class="pd-buttons-bp">'.$pdMirrorLink1.'</div>';
			} else if ($this->t['display_mirror_links'] == 2 || $this->t['display_mirror_links'] == 3) {
				echo '<div class="pd-mirrors">'.$pdMirrorLink2.$pdMirrorLink1.'</div>';
			}

			echo '<div class="pd-report">'.$pdReportLink.'</div>';
			echo '<div class="pd-tags">'.$pdTags.'</div>';
			echo '<div class="pd-cb"></div>';
			echo '</div>';

			echo '</div></div>'; // end col, end row


			$o = '<div class="pd-cb">&nbsp;</div>';

			if ((int)$v->confirm_license > 0) {
				$o .= '<h4 class="pdfv-confirm-lic-text">'.JText::_('COM_PHOCADOWNLOAD_LICENSE_AGREEMENT').'</h4>';
				$o .= '<div id="phoca-dl-license" style="height:'.(int)$this->t['licenseboxheight'].'px">'.$v->licensetext.'</div>';

				// External link
				if ($v->link_external != '' && $v->directlink == 1) {
					$o .= '<form action="" name="phocaDownloadForm" id="phocadownloadform" target="'.$this->t['download_external_link'].'">';
					$o .= '<input type="checkbox" name="license_agree" onclick="enableDownloadPD()" /> <span>'.JText::_('COM_PHOCADOWNLOAD_I_AGREE_TO_TERMS_LISTED_ABOVE').'</span> ';
					$o .= '<input class="btn btn-success" type="button" name="submit" onClick="location.href=\''.$v->link_external.'\';" id="pdlicensesubmit" value="'.JText::_('COM_PHOCADOWNLOAD_DOWNLOAD').'" />';
				} else {
					$o .= '<form action="'.htmlspecialchars($this->t['action']).'" method="post" name="phocaDownloadForm" id="phocadownloadform">';
					$o .= '<input type="checkbox" name="license_agree" onclick="enableDownloadPD()" /> <span>'.JText::_('COM_PHOCADOWNLOAD_I_AGREE_TO_TERMS_LISTED_ABOVE').'</span> ';
					$o .= '<input class="btn btn-success" type="submit" name="submit" id="pdlicensesubmit" value="'.JText::_('COM_PHOCADOWNLOAD_DOWNLOAD').'" />';
					$o .= '<input type="hidden" name="download" value="'.$v->id.'" />';
					$o .= '<input type="hidden" name="'. JSession::getFormToken().'" value="1" />';
				}
				$o .= '</form>';

				// For users who have disabled Javascript
				$o .= '<script type=\'text/javascript\'>document.forms[\'phocadownloadform\'].elements[\'pdlicensesubmit\'].disabled=true</script>';
			} else {
				// External link
				if ($v->link_external != '') {
					$o .= '<form action="" name="phocaDownloadForm" id="phocadownloadform" target="'.$this->t['download_external_link'].'">';
					$o .= '<input class="btn btn-success" type="button" name="submit" onClick="location.href=\''.$v->link_external.'\';" id="pdlicensesubmit" value="'.JText::_('COM_PHOCADOWNLOAD_DOWNLOAD').'" />';
				} else {
					$o .= '<form action="'.htmlspecialchars($this->t['action']).'" method="post" name="phocaDownloadForm" id="phocadownloadform">';
					$o .= '<input class="btn btn-success" type="submit" name="submit" id="pdlicensesubmit" value="'.JText::_('COM_PHOCADOWNLOAD_DOWNLOAD').'" />';
					$o .= '<input type="hidden" name="license_agree" value="1" />';
					$o .= '<input type="hidden" name="download" value="'.$v->id.'" />';
					$o .= '<input type="hidden" name="'. JSession::getFormToken().'" value="1" />';
				}
				$o .= '</form>';
			}


			if ($this->t['display_file_comments'] == 1) {
				if (JComponentHelper::isEnabled('com_jcomments', true)) {
					include_once(JPATH_BASE.'/components/com_jcomments/jcomments.php');
					$o .= JComments::showComments($v->id, 'com_phocadownload_files', JText::_('COM_PHOCADOWNLOAD_FILE') .' '. $v->title);
				}
			}

			if ($this->t['display_file_comments'] == 2) {
				$o .= '<div class="pd-fbcomments">'.$this->loadTemplate('comments-fb').'</div>';
			}

			echo '<div class="row ">';
			echo '<div class="col-sm-12 col-md-12">';
			echo $o;
			echo '</div></div>'; // end col, end row

		} else {
			echo '<div class="row ">';
			echo '<div class="col-sm-12 col-md-12">';
			echo '<h3 class="pd-filename-txt">'.JText::_('COM_PHOCADOWNLOAD_FILE') .'</h3>';
			echo '<div class="pd-error">'.JText::_('COM_PHOCADOWNLOAD_NO_RIGHTS_ACCESS_CATEGORY').'</div>';
			echo '</div></div>'; // end col, end row
		}
	}
	echo '<div>&nbsp;</div>';// end of box
} else {
	echo '<div>&nbsp;</div>';
}
echo '</div></div>';
echo '<div class="pd-cb">&nbsp;</div>';
echo PhocaDownloadUtils::getInfo();
?>
