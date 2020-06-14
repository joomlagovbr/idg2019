<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

echo '<div id="phoca-dl-download-box" class="pd-download-view'.$this->t['p']->get( 'pageclass_sfx' ).'" >';
echo '<div class="pd-download">';

if ($this->t['found'] == 1) {
	if(isset($this->file[0]->id) && (int)$this->file[0]->id > 0 && isset($this->file[0]->token) && $this->file[0]->token != '') {

		$v = $this->file[0];
		$downloadLink = PhocaDownloadRoute::getDownloadRoute((int)$v->id, (int)$v->catid, $v->token);
		$l = new PhocaDownloadLayout();

		//echo '<h3 class="pdfv-name">'.$l->getName($v->title, $v->filename, 1). '</h3>';

		$pdTitle = '';
		if ($v->title != '') {
			$pdTitle .= '<div class="pd-title">'.$v->title.'</div>';
		}

		$pdImage = '';
		if ($v->image_download != '') {
			$pdImage .= '<div class="pd-image">'.$l->getImageDownload($v->image_download).'</div>';
		}

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

			// Tags - title suffix - FILE VIEW = DOWNLOAD FILE
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
		echo '<div class="pd-downloadbox-direct">'
		.$pdFile
		.'<div style="clear:both"></div>'
		.'<div class="pd-center pd-download-direct pd-button-download"><a class="btn btn-success btn-large" href="'.JRoute::_($downloadLink).'">'.JText::_('COM_PHOCADOWNLOAD_DOWNLOAD_FILE').'</a></div></div>';

	}
} else {
	echo '<div class="pd-not-found">'.JText::_('COM_PHOCADOWNLOAD_FILE_NOT_FOUND').'</div>';
}


echo '</div></div><div class="pd-cb">&nbsp;</div>';
echo PhocaDownloadUtils::getInfo();
?>
