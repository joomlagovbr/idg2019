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
class PhocaDownloadLayout
{
	public $params;
	public $filePath;
	public $iconPath;
	public $cssImagePath;
	public $fileAbsPath;

	public function __construct() {
		if (empty($params)) {
			$this->params = JComponentHelper::getParams('com_phocadownload') ;
		}

		if ($this->filePath == '') {
			$this->filePath		= PhocaDownloadPath::getPathSet('file');
		}

		if ($this->iconPath == '') {
			$this->iconPath		= PhocaDownloadPath::getPathSet('icon');
		}

		if ($this->cssImagePath == '') {
			$this->cssImagePath	= str_replace ( '../', JURI::base(true).'/', $this->iconPath['orig_rel_ds']);
		}

		if ($this->fileAbsPath == '') {
			$this->fileAbsPath	= $this->filePath['orig_abs_ds'];
		}

	}


	public function getName($title, $filename, $preferTitle = 0) {

		$name	= $title;
		$fon	= $this->params->get( 'filename_or_name', 'filename' );

		if ($fon == 'title') {
			$name = $title;
		} else if ($fon == 'filename'){
			$name = PhocaDownloadFile::getTitleFromFilenameWithExt( $filename );
		} else if ($fon == 'filenametitle'){
			if ($preferTitle == 1) {
				$name = $title;
			} else {
				// Must be solved before
				$name = PhocaDownloadFile::getTitleFromFilenameWithExt( $filename );
			}
		}

		return $name;
	}

	public function getImageFileName($imageFilename, $fileName = '', $type = 1, $iconSize = 16) {

		$name['filenamestyle'] 	= '';
		$name['filenamethumb']	= '';

		if ($imageFilename !='') {
			$thumbnail = false;
			$thumbnail = preg_match("/phocathumbnail/i", $imageFilename);
			if ($thumbnail) {
				$name['filenamethumb']	= '<div class="pdfv-image-file-thumb" >'
				.'<img src="'.$this->cssImagePath.$imageFilename.'" alt="" /></div>';
				$name['filenamestyle']	= '';
			} else {
				$name['filenamethumb']	= '';
				$name['filenamestyle'] 	= 'style="background: url(\''.$this->cssImagePath.$imageFilename.'\') 0 center no-repeat;"';
			}
		} else {
			$file_icon_mime = $this->params->get( 'file_icon_mime', 1 );
			if ($fileName != '' && $file_icon_mime == 1) {
				if ($type == 3) { // Plugin
					$file_icon_size = $iconSize;
				} else if ($type == 2) {
					$file_icon_size = $this->params->get( 'file_icon_size_md', 16 );
				} else {
					$file_icon_size = $this->params->get( 'file_icon_size', 16 );
				}
				$icon = PhocaDownloadFile::getMimeTypeIcon($fileName, (int)$file_icon_size, 1);
				$name['filenamethumb']	= '';
				$name['filenamestyle'] 	= $icon;
			}
		}


		return $name;
	}

	public function getFileSize($filename) {

		$size = '';
		if ($filename != '') {
			$absFile = str_replace('\\', '/', JPath::clean($this->fileAbsPath . $filename));
			if (JFile::exists($absFile)) {
				$size = PhocaDownloadFile::getFileSizeReadable(filesize($absFile));
			} else {
				$size = '';
			}
		}

		return $size;
	}

	public function getProtectEmail($email) {

		$email = str_replace('@', '['.JText::_('COM_PHOCADOWNLOAD_AT').']', $email);
		$email = str_replace('.', '['.JText::_('COM_PHOCADOWNLOAD_DOT').']', $email);

		return $email;
	}

	public function getFileDate($filename, $date) {

		$dateO 	= '';
		$ddt	= $this->params->get( 'display_date_type', 0 );
		if ((int)$ddt > 0) {
			if ($filename !='') {
				$dateO = PhocaDownloadFile::getFileTime($filename, $ddt);
			}
		} else {
			$dateO = JHTML::Date($date, JText::_('DATE_FORMAT_LC3'));
		}

		return $dateO;
	}

	public function isValueEditor($text) {

		if ($text != '' && $text != '<p>&#160;</p>' && $text != '<p>&nbsp;</p>' && $text != '<p></p>' && $text != '<br />') {
			return true;
		}
		return false;
	}

	public function getImageDownload($img) {

		return '<img src="'.$this->cssImagePath . $img.'" alt="" />';
	}

	public function displayTags($fileId, $type = 0) {

		$o = '';
		$db = JFactory::getDBO();

		$query = 'SELECT a.id, a.title, a.link_ext, a.link_cat'
		.' FROM #__phocadownload_tags AS a'
		.' LEFT JOIN #__phocadownload_tags_ref AS r ON r.tagid = a.id'
		.' WHERE r.fileid = '.(int)$fileId
		.' ORDER BY a.id';

		$db->setQuery($query);

        try {
            $fileIdObject = $db->loadObjectList();
        } catch (\Exception $e) {
        	throw new \Exception($e->getMessage(), 500);
        }

        $tl	= $this->params->get( 'tags_links', 0 );

		$class = '';
		if ($type == 1) {
			$class = 'class="label label-default"';
		}

		foreach ($fileIdObject as $k => $v) {
			$o .= '<span '.$class.'>';
			if ($tl == 0) {
				$o .= $v->title;
			} else if ($tl == 1) {
				if ($v->link_ext != '') {
					$o .= '<a href="'.$v->link_ext.'">'.$v->title.'</a>';
				} else {
					$o .= $v->title;
				}
			} else if ($tl == 2) {

				if ($v->link_cat != '') {
					$query = 'SELECT a.id, a.alias'
					.' FROM #__phocadownload_categories AS a'
					.' WHERE a.id = '.(int)$v->link_cat
					.' ORDER BY a.id';

					$db->setQuery($query, 0, 1);


                    try {
                        $category = $db->loadObject();
                    } catch (\RuntimeException $e) {
                        throw new \Exception($e->getMessage(), 500);
                    }

					if (isset($category->id) && isset($category->alias)) {
						$link = PhocaDownloadRoute::getCategoryRoute($category->id, $category->alias);
						$o .= '<a href="'.$link.'">'.$v->title.'</a>';
					} else {
						$o .= $v->title;
					}
				} else {
					$o .= $v->title;
				}
			} else if ($tl == 3) {
				$link = PhocaDownloadRoute::getCategoryRouteByTag($v->id);
				$o .= '<a href="'.$link.'">'.$v->title.'</a>';
			}

			$o .= '</span> ';
		}

		return $o;
	}

	public function displayTagsString($string = '') {
		$o = array();
		if ($string != '') {
			$sA = explode(',', $string);
			if (!empty($sA)) {
				foreach ($sA as $k => $v) {

					// Specific cases for Joomla! CMS
					switch($v) {
						case '1.5': $c = 'pd-j-15'; break;
						case '1.7': $c = 'pd-j-17'; break;
						case '2.5': $c = 'pd-j-25'; break;
						case '3.x': $c = 'pd-j-3x'; break;
						case '3.5': $c = 'pd-j-35'; break;
						default: $c = 'label-default';break;
					}

					$o[] = '<span class="label '.$c.'">'.$v.'</span>';
				}
			}
		}
		return implode(" ", $o);

	}


	public function displayVideo($url, $view = 0, $ywidth = 0, $yheight = 0) {

		$o = '';

		$app			= JFactory::getApplication();


		if ($view == 0) {
			// Category View
			$height	= $this->params->get( 'youtube_height_cv', 240 );
			$width	= $this->params->get( 'youtube_width_cv', 320 );
		} else {
			// Detail View
			$height	= $this->params->get( 'youtube_height_dv', 360 );
			$width	= $this->params->get( 'youtube_width_dv', 480 );
		}

		if ($url != '' && PhocaDownloadUtils::isURLAddress($url) ) {


			$ssl 	= strpos($url, 'https');
			$yLink	= 'http://www.youtube.com/v/';
			if ($ssl != false) {
				$yLink = 'https://www.youtube.com/v/';
			}

			$shortUrl	= 'http://youtu.be/';
			$shortUrl2	= 'https://youtu.be/';
			$pos 		= strpos($url, $shortUrl);
			$pos2 		= strpos($url, $shortUrl2);
			if ($pos !== false) {
				$code 		= str_replace($shortUrl, '', $url);
			} else if ($pos2 !== false) {
				$code 		= str_replace($shortUrl2, '', $url);
			} else {
				$codeArray 	= explode('=', $url);
				$code 		= str_replace($codeArray[0].'=', '', $url);
			}



			if ((int)$ywidth > 0) {
				$width	= (int)$ywidth;
			}
			if ((int)$yheight > 0) {
				$height	= (int)$yheight;
			}

			$attr = '';
			if ((int)$width > 0) {
				$attr .= ' width="'.(int)$width.'"';
			}
			if ((int)$height > 0) {
				$attr .= ' height="'.(int)$height.'"';
			}

			$o .= '<div class="ph-video-container">';
			$o .= '<iframe '.$attr.' src="https://www.youtube.com/embed/'.$code.'"></iframe>';
			$o .= '</div>';
			/*$o .= '<object height="'.(int)$height.'" width="'.(int)$width.'" data="http://www.youtube.com/v/'.$code.'" type="application/x-shockwave-flash">'
			.'<param name="movie" value="http://www.youtube.com/v/'.$code.'" />'
			.'<param name="allowFullScreen" value="true" />'
			.'<param name="allowscriptaccess" value="always" />'
			.'<embed src="'.$yLink.$code.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="'.(int)$height.'" width="'.(int)$width.'" /></object>';*/
		}
		return $o;
	}
}
?>
