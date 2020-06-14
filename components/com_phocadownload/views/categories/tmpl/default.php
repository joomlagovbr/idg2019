<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

echo '<div id="phoca-dl-categories-box" class="pd-categories-view'.$this->t['p']->get( 'pageclass_sfx' ).'">';

if ( $this->t['p']->get( 'show_page_heading' ) ) {
	echo '<h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1>';
}

if ( $this->t['description'] != '') {
	echo '<div class="pd-desc">'. JHTML::_('content.prepare', $this->t['description']) . '</div>';
}


if (!empty($this->t['categories'])) {
	$i = 1;
	foreach ($this->t['categories'] as $value) {

		// Categories
		$numDoc 	= 0;
		$numSubcat	= 0;
		$catOutput 	= '';
		if (!empty($value->subcategories)) {
			foreach ($value->subcategories as $valueCat) {

				// USER RIGHT - Access of categories - - - - -
				// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
				$rightDisplay	= 0;
				if (!empty($valueCat)) {
					$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $valueCat->accessuserid, $valueCat->access, $this->t['user']->getAuthorisedViewLevels(), $this->t['user']->get('id', 0), 0);

				}
				// - - - - - - - - - - - - - - - - - - - - - -

				if ($rightDisplay == 1) {

					$catOutput 	.= '<div class="pd-subcategory">';
					$catOutput 	.= '<a href="'. JRoute::_(PhocaDownloadRoute::getCategoryRoute($valueCat->id, $valueCat->alias))
								.'">'. $valueCat->title.'</a>';

					if ($this->t['displaynumdocsecs'] == 1) {
						$catOutput  .=' <small>('.$valueCat->numdoc .')</small>';
					}
					$catOutput 	.= '</div>' . "\n";
					$numDoc = (int)$valueCat->numdoc + (int)$numDoc;
					$numSubcat++;
				}
			}
		}

		// Don't display parent category
		// - if there is no catoutput
		// - if there is no rigths for it

		// USER RIGHT - Access of parent category - - - - -
		// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
		$rightDisplay	= 0;
		if (!empty($value)) {
			$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $value->accessuserid, $value->access, $this->t['user']->getAuthorisedViewLevels(), $this->t['user']->get('id', 0), 0);

		}
		// - - - - - - - - - - - - - - - - - - - - - -

		if ($rightDisplay == 1) {

// =====================================================================================
// BEGIN LAYOUT AREA
// =====================================================================================

			$pdTitle = '<a href="'. JRoute::_(PhocaDownloadRoute::getCategoryRoute($value->id, $value->alias)).'">'. $value->title.'</a>';

			if ($this->t['displaynumdocsecsheader'] == 1) {
				$numDocAll = (int)$numDoc + (int)$value->numdoc;
				//$numDoc ... only files in subcategories
				//$value->numdoc ... only files in the main category
				//$numDocAll ... files in category and in subcategories
				$pdTitle .= ' <small>('.$numSubcat.'/' . $numDocAll .')</small>';
			}


			$pdDesc = '';
			$pdSubcategories = '';
			$pdImg = '';
			if (isset($value->image) && $value->image != '') {
				$pdImg = '<img src="'.$this->t['cssimgpath'].$value->image.'" alt="'.htmlspecialchars(strip_tags($value->title)).'" />';
			}

			if ($this->t['displaymaincatdesc']	 == 1) {
				$pdDesc .= JHTML::_('content.prepare', $value->description);
			} else {
				if ($catOutput != '') {
					$pdSubcategories .= $catOutput;
				} else {
					$pdSubcategories .= '<div class="pd-no-subcat">'.JText::_('COM_PHOCADOWNLOAD_NO_SUBCATEGORIES').'</div>';
				}
			}

			$pdClear = '';
			if ($i%3==0) {
				$pdClear .= '<div class="pd-cb"></div>';
			}
			$i++;




			// ---------------------------------------------------
			//Convert
			// ---------------------------------------------------
			if ($this->t['display_specific_layout'] == 0) {
				echo '<div class="pd-categoriesbox">';
				echo '<div class="pd-title">'.$pdTitle.'</div>';
				if ($pdImg != '') { echo '<div class="ph-img">'.$pdImg.'</div>';}
				if ($pdDesc != '') { echo '<div class="pd-desc">'.$pdDesc.'</div>';}
				echo $pdSubcategories;
				echo '</div>';
				echo $pdClear;
			} else {
				$categoriesLayout = PhocaDownloadSettings::getLayoutText('categories');

				/*'<div class="pd-categoriesbox">
				<div class="pd-title">{pdtitle}</div>
				{pdsubcategories}
				{pdclear}
				</div>';
				//<div class="pd-desc">{pdDescription}</div>*/

				$categoriesLayoutParams 	= PhocaDownloadSettings::getLayoutParams('categories');

				$replace	= array($pdTitle, $pdDesc, $pdSubcategories, $pdClear);
				$output		= str_replace($categoriesLayoutParams['search'], $replace, $categoriesLayout);

				echo $output;
			}
		}
	}
}
echo '</div>'
    .'<div class="pd-cb"></div>';


// - - - - - - - - - -
// Most viewed docs (files)
// - - - - - - - - - -
$outputFile		= '';

if (!empty($this->t['mostvieweddocs']) && $this->t['displaymostdownload'] == 1) {
	$l = new PhocaDownloadLayout();
	foreach ($this->t['mostvieweddocs'] as $value) {
		// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
		// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
		$rightDisplay	= 0;
		if (!empty($value)) {
			$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $value->cataccessuserid, $value->cataccess, $this->t['user']->getAuthorisedViewLevels(), $this->t['user']->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - - - -

		if ($rightDisplay == 1) {
			// FILESIZE
			if ($value->filename !='') {
				$absFile = str_replace('/', '/', JPath::clean($this->t['absfilepath'] . $value->filename));
				if (JFile::exists($absFile)) {
					$fileSize = PhocaDownloadFile::getFileSizeReadable(filesize($absFile));
				} else {
					$fileSize = '';
				}
			}

			// IMAGE FILENAME
			//$imageFileName = '';
			//if ($value->image_filename !='') {
				$imageFileName = $l->getImageFileName($value->image_filename, $value->filename, 2);
				/*$thumbnail = false;
				$thumbnail = preg_match("/phocathumbnail/i", $value->image_filename);
				if ($thumbnail) {
					$imageFileName 	= '';
				} else {
					$imageFileName = 'style="background: url(\''.$this->t['cssimgpath'].$value->image_filename.'\') 0 center no-repeat;"';
				}*/
			//}

			//$outputFile .= '<div class="pd-document'.$this->t['file_icon_size_md'].'" '.$imageFileName.'>';

			$outputFile .= '<div class="pd-filename">'. $imageFileName['filenamethumb']
					. '<div class="pd-document'.$this->t['file_icon_size_md'].'" '
					. $imageFileName['filenamestyle'].'>';

			$outputFile .= '<a href="'
						. JRoute::_(PhocaDownloadRoute::getCategoryRoute($value->categoryid,$value->categoryalias))
						.'">'. $value->title.'</a>'
						.' <small>(' .$value->categorytitle.')</small>';

			$outputFile .= PhocaDownloadRenderFront::displayNewIcon($value->date, $this->t['displaynew']);
			$outputFile .= PhocaDownloadRenderFront::displayHotIcon($value->hits, $this->t['displayhot']);

			$outputFile .= '</div></div>' . "\n";
		}
	}

	if ($outputFile != '') {
		echo '<div class="pd-hr" style="clear:both">&nbsp;</div>';
		echo '<div id="phoca-dl-most-viewed-box">';
		echo '<div class="pd-documents"><h3>'. JText::_('COM_PHOCADOWNLOAD_MOST_DOWNLOADED_FILES').'</h3>';
		echo $outputFile;
		echo '</div></div>';
	}
}
echo '<div class="pd-cb">&nbsp;</div>';
echo PhocaDownloadUtils::getInfo();
?>
