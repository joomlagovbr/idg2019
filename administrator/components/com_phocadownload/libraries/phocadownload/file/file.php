<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

class PhocaDownloadFile
{
	/*
	 * http://aidanlister.com/repos/v/function.size_readable.php
	 */
	public static function getFileSizeReadable ($size, $retstring = null, $onlyMB = false) {

		if ($onlyMB) {
			$sizes = array('B', 'kB', 'MB');
		} else {
			$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }


		if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);

        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }

        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
	}

	public static function getMimeTypeIcon($filename, $size = 16, $outcome = 0) {
		$ext = JFile::getExt($filename);
		switch(strtolower($ext)) {



			case 'html':
			case 'htm':
				$icon = 'html';
			break;

			case 'c':
			case 'js':
			case 'py':
			case 'rp':
				$icon = 'source';
			break;

			case 'xml':
				$icon = 'xml';
			break;

			case 'odp':
			case 'ppt':
			case 'pps':
			case 'ppsx':
			case 'pptx':
			case 'pptm':
			case 'ppsm':
				$icon = 'presentation';
			break;

			case 'ods':
			case 'xls':
			case 'xlsx':
				$icon = 'spreadsheet';
			break;

			case 'odt':
			case 'doc':
			case 'docx':
			case 'dotx':
				$icon = 'document';
			break;

			case 'php':
				$icon = 'php';
			break;

			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'bmp':
				$icon = 'img';
			break;

			case 'jar':
				$icon = 'jar';
			break;

			case 'pdf':
				$icon = 'pdf';
			break;

			case 'sql':
				$icon = 'sql';
			break;

			case 'svg':
			case 'ai':
			case 'cdr':
				$icon = 'drawing';
			break;

			case 'txt':
			case 'ini':
				$icon = 'txt';
			break;


			case '7z':
				$icon = '7zip';
			break;
			case 'gz':
				$icon = 'gzip';
			break;
			case 'rar':
				$icon = 'rar';
			break;
			case 'tar':
				$icon = 'tar';
			break;
			case 'zip':
			case 'bzip':
				$icon = 'zip';
			break;

			case 'flv':
			case 'avi':
			case 'mp4':
			case 'mpeg':
			case 'ogv':
				$icon = 'video';
			break;

			case 'ogg':
			case 'mp3':
			case 'wav':
			case 'mid':
				$icon = 'audio';
			break;

			default:
				$icon = 'empty';
			break;
		}

		if ($outcome == 1) {
			return 'style="background: url(\''.JURI::root(). 'media/com_phocadownload/images/mime/'.(int)$size.'/icon-'. htmlspecialchars($icon).'.png\') 0 center no-repeat;"';
		} else {
			return '<img src="'.JURI::root(). 'media/com_phocadownload/images/mime/'.(int)$size.'/icon-'. htmlspecialchars($icon). '.png'.'" alt="" />';
		}

		return $mime;
	}

	public static function existsCss($file, $type) {
		$path = self::getCSSPath($type);
		if (file_exists($path.$file) && $file != '') {
			return $path.$file;
		}
		return false;
	}

	public static function getCSSPath($type, $rel = 0) {
		$paths		= PhocaDownloadPath::getPathMedia();
		if ($rel == 1) {
			if ($type == 1) {
				return $paths->media_css_rel . 'main/';
			} else {
				return $paths->media_css_rel . 'custom/';
			}
		} else {
			if ($type == 1) {
				return JPath::clean($paths->media_css_abs . 'main/');
			} else {
				return	JPath::clean($paths->media_css_abs . 'custom/');
			}
		}
	}

	public static function getCSSFile($id = 0, $fullPath = 0) {
		if ((int)$id > 0) {
			$db = JFactory::getDBO();
			$query = 'SELECT a.filename as filename, a.type as type'
				.' FROM #__phocadownload_styles AS a'
			    .' WHERE a.id = '.(int) $id
				.' ORDER BY a.id';
			$db->setQuery($query, 0, 1);
			$filename = $db->loadObject();

			if (isset($filename->filename) && $filename->filename != '') {
				if ($fullPath == 1 && isset($filename->type)) {
					return self::getCSSPath($filename->type). $filename->filename;
				} else {
					return $filename->filename;
				}
			}
		}

		return false;
	}

	public static function getFileSize($filename, $readable = 1) {

		$path			= PhocaDownloadPath::getPathSet();
		$fileNameAbs	= JPath::clean($path['orig_abs'] . '/' . $filename);

		if ($readable == 1) {
			return self::getFileSizeReadable(filesize($fileNameAbs));
		} else {
			return filesize($fileNameAbs);
		}
	}



	public static function getFileTime($filename, $function, $format = DATE_FORMAT_LC3) {

		$path			= PhocaDownloadPath::getPathSet();
		$fileNameAbs	= JPath::clean($path['orig_abs'] . '/' . $filename);
		if (JFile::exists($fileNameAbs)) {
			switch($function) {
				case 2:
					$fileTime = filectime($fileNameAbs);
				break;
				case 3:
					$fileTime = fileatime($fileNameAbs);
				break;
				case 1:
				default:
					$fileTime = filemtime($fileNameAbs);
				break;
			}

			$fileTime = JHTML::Date($fileTime, $format);
		} else {
			$fileTime = '';
		}
		return $fileTime;
	}



	public static function getTitleFromFilenameWithExt (&$filename) {
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)

		return $folder_array[$last_array_value];
	}


	public static function getMimeType($extension, $params) {

		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$returnMime = '';

		for($i = 0; $i < $count_matches; $i++) {

			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);

			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}

			// Return mime if extension call it
			if ($extension == $values[0]) {
				$returnMime = $values[1];
			}
		}

		if ($returnMime != '') {
			return $returnMime;
		} else {
			return "PhocaErrorNoMimeFound";
		}
	}

	public static function getMimeTypeString($params) {

		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$extString 	= '';
		$mimeString	= '';

		for($i = 0; $i < $count_matches; $i++) {

			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);

			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}

			// Create strings
			$extString .= $values[0];
			$mimeString .= $values[1];

			$j = $i + 1;
			if ($j < $count_matches) {
				$extString .=',';
				$mimeString .=',';
			}
		}

		$string 		= array();
		$string['mime']	= $mimeString;
		$string['ext']	= $extString;

		return $string;
	}

	public static function getTitleFromFilenameWithoutExt (&$filename) {

		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)

		$string = false;
		$string = preg_match( "/\./i", $folder_array[$last_array_value] );
		if ($string) {
			return PhocaDownloadFile::removeExtension($folder_array[$last_array_value]);
		} else {
			return $folder_array[$last_array_value];
		}
	}

	public static function getFolderFromTheFile($filename) {

		$folder_array		= explode('/', $filename);
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;
		return str_replace($folder_array[$last_array_value], '', $filename);
	}

	public static function removeExtension($file_name) {
		return substr($file_name, 0, strrpos( $file_name, '.' ));
	}

	public static function getExtension( $file_name ) {
		return strtolower( substr( strrchr( $file_name, "." ), 1 ) );
	}

	public static function canPlay( $fileName ) {
		$fileExt 	= PhocaDownloadFile::getExtension($fileName);

		$paramsC	= JComponentHelper::getParams('com_phocadownload');
		$html5		= $paramsC->get( 'html5_play', 1 );

		if ($html5 == 1) {
			switch($fileExt) {
				case 'mp3':
				case 'mp4':
				case 'flv':
				case 'ogg':
				case 'ogv':
					return true;
				break;

				default:
					return false;
				break;
			}
		} else {
			switch($fileExt) {
				case 'mp3':
				case 'mp4':
				case 'flv':
				//case 'mov':
				//case 'wmv':
					return true;
				break;

				default:
					return false;
				break;
			}
		}
		return false;
	}

	public static function canPreview( $fileName ) {
		$fileExt 	= PhocaDownloadFile::getExtension($fileName);

		switch($fileExt) {
			case 'pdf':
			case 'jpeg':
			case 'jpg':
			case 'png':
			case 'gif':
				return true;
			break;

			default:
				return false;
			break;

		}
		return false;
	}
}
?>
