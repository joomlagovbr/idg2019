<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaDownloadSettings
{
	public static function getManagerGroup($manager) {

		$group = array();
		switch ($manager) {
			case 'icon':
			case 'iconspec1':
			case 'iconspec2':
				$group['f'] = 2;//File
				$group['i'] = 1;//Image
				$group['t'] = 'icon';//Text
				$group['c']	= '&amp;tmpl=component';
			break;


			case 'image':
				$group['f'] = 2;//File
				$group['i'] = 1;//Image
				$group['t'] = 'image';//Text
				$group['c']	= '&amp;tmpl=component';
			break;

			case 'filepreview':
				$group['f'] = 3;
				$group['i'] = 1;
				$group['t'] = 'filename';
				$group['c']	= '&amp;tmpl=component';
			break;

			case 'fileplay':
				$group['f'] = 3;
				$group['i'] = 0;
				$group['t'] = 'filename';
				$group['c']	= '&amp;tmpl=component';
			break;

			case 'filemultiple':
				$group['f'] = 1;
				$group['i'] = 0;
				$group['t'] = 'filename';
				$group['c']	= '';
			break;

			case 'file':
			default:
				$group['f'] = 1;
				$group['i'] = 0;
				$group['t'] = 'filename';
				$group['c']	= '&amp;tmpl=component';
			break;
		}
		return $group;
	}

	public static function getDefaultAllowedMimeTypesDownload() {
		return '{hqx=application/mac-binhex40}{cpt=application/mac-compactpro}{csv=text/x-comma-separated-values}{bin=application/macbinary}{dms=application/octet-stream}{lha=application/octet-stream}{lzh=application/octet-stream}{exe=application/octet-stream}{class=application/octet-stream}{psd=application/x-photoshop}{so=application/octet-stream}{sea=application/octet-stream}{dll=application/octet-stream}{oda=application/oda}{pdf=application/pdf}{ai=application/postscript}{eps=application/postscript}{ps=application/postscript}{smi=application/smil}{smil=application/smil}{mif=application/vnd.mif}{xls=application/vnd.ms-excel}{ppt=application/powerpoint}{wbxml=application/wbxml}{wmlc=application/wmlc}{dcr=application/x-director}{dir=application/x-director}{dxr=application/x-director}{dvi=application/x-dvi}{gtar=application/x-gtar}{gz=application/x-gzip}{php=application/x-httpd-php}{php4=application/x-httpd-php}{php3=application/x-httpd-php}{phtml=application/x-httpd-php}{phps=application/x-httpd-php-source}{js=application/x-javascript}{swf=application/x-shockwave-flash}{sit=application/x-stuffit}{tar=application/x-tar}{tgz=application/x-tar}{xhtml=application/xhtml+xml}{xht=application/xhtml+xml}{zip=application/x-zip}{mid=audio/midi}{midi=audio/midi}{mpga=audio/mpeg}{mp2=audio/mpeg}{mp3=audio/mpeg}{aif=audio/x-aiff}{aiff=audio/x-aiff}{aifc=audio/x-aiff}{ram=audio/x-pn-realaudio}{rm=audio/x-pn-realaudio}{rpm=audio/x-pn-realaudio-plugin}{ra=audio/x-realaudio}{rv=video/vnd.rn-realvideo}{wav=audio/x-wav}{bmp=image/bmp}{gif=image/gif}{jpeg=image/jpeg}{jpg=image/jpeg}{jpe=image/jpeg}{png=image/png}{tiff=image/tiff}{tif=image/tiff}{css=text/css}{html=text/html}{htm=text/html}{shtml=text/html}{txt=text/plain}{text=text/plain}{log=text/plain}{rtx=text/richtext}{rtf=text/rtf}{xml=text/xml}{xsl=text/xml}{mpeg=video/mpeg}{mpg=video/mpeg}{mpe=video/mpeg}{qt=video/quicktime}{mov=video/quicktime}{avi=video/x-msvideo}{flv=video/x-flv}{movie=video/x-sgi-movie}{doc=application/msword}{xl=application/excel}{eml=message/rfc822}{pptx=application/vnd.openxmlformats-officedocument.presentationml.presentation}{xlsx=application/vnd.openxmlformats-officedocument.spreadsheetml.sheet}{docx=application/vnd.openxmlformats-officedocument.wordprocessingml.document}{rar=application/x-rar-compressed}{odb=application/vnd.oasis.opendocument.database}{odc=application/vnd.oasis.opendocument.chart}{odf=application/vnd.oasis.opendocument.formula}{odg=application/vnd.oasis.opendocument.graphics}{odi=application/vnd.oasis.opendocument.image}{odm=application/vnd.oasis.opendocument.text-master}{odp=application/vnd.oasis.opendocument.presentation}{ods=application/vnd.oasis.opendocument.spreadsheet}{odt=application/vnd.oasis.opendocument.text}{sxc=application/vnd.sun.xml.calc}{sxd=application/vnd.sun.xml.draw}{sxg=application/vnd.sun.xml.writer.global}{sxi=application/vnd.sun.xml.impress}{sxm=application/vnd.sun.xml.math}{sxw=application/vnd.sun.xml.writer}{mp4=video/mp4}{mp4=application/octet-stream}';
	}

	public static function getDefaultAllowedMimeTypesUpload() {
		return '{pdf=application/pdf}{ppt=application/powerpoint}{gz=application/x-gzip}{tar=application/x-tar}{tgz=application/x-tar}{zip=application/x-zip}{bmp=image/bmp}{gif=image/gif}{jpeg=image/jpeg}{jpg=image/jpeg}{jpe=image/jpeg}{png=image/png}{tiff=image/tiff}{tif=image/tiff}{txt=text/plain}{mpeg=video/mpeg}{mpg=video/mpeg}{mpe=video/mpeg}{qt=video/quicktime}{mov=video/quicktime}{avi=video/x-msvideo}{flv=video/x-flv}{doc=application/msword}{mp4=video/mp4}{mp4=application/octet-stream}';
	}

	public static function getHTMLTagsUpload() {
		return array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
	}

	public static function getLayoutText($type) {

		$db = JFactory::getDBO();

		$query = 'SELECT a.'.$type
		.' FROM #__phocadownload_layout AS a';

		$db->setQuery($query, 0, 1);
		$layout = $db->loadObject();

		/*if (!$db->query()) {
			throw new Exception($db->getErrorMsg(), 500);
			return false;
		}*/

		if (isset($layout->$type)) {
			return $layout->$type;
		}

		return '';

	}

	public static function getLayoutParams($type) {

		$params = array();
		switch($type) {

			case 'categories':
				$params['style']		= array('pd-title','pd-desc', 'pd-subcategory', 'pd-no-subcat');//'pd-',
				$params['search']		= array('{pdtitle}','{pddescription}', '{pdsubcategories}', '{pdclear}');
			break;

			case 'category':
				$params['style']		= array('pd-title','pd-image', 'pd-file', 'pd-fdesc', 'pd-mirrors', 'pd-mirror', 'pd-report', 'pd-rating', 'pd-tags', 'pd-buttons', 'pd-downloads', 'pd-video');
				$params['search']		= array('{pdtitle}','{pdimage}', '{pdfile}', '{pdfilesize}', '{pdversion}', '{pdlicense}', '{pdauthor}', '{pdauthoremail}', '{pdfiledate}', '{pddownloads}', '{pddescription}', '{pdfeatures}', '{pdchangelog}', '{pdnotes}', '{pdmirrorlink1}', '{pdmirrorlink2}', '{pdreportlink}', '{pdrating}', '{pdtags}', '{pdfiledesctop}', '{pdfiledescbottom}', '{pdbuttondownload}', '{pdbuttondetails}', '{pdbuttonpreview}', '{pdbuttonplay}', '{pdvideo}');
			break;


			case 'file':
				$params['style']		= array('pd-title','pd-image', 'pd-file', 'pd-fdesc', 'pd-mirrors', 'pd-mirror', 'pd-report', 'pd-rating', 'pd-tags', 'pd-downloads', 'pd-video');
				$params['search']		= array('{pdtitle}','{pdimage}', '{pdfile}', '{pdfilesize}', '{pdversion}', '{pdlicense}', '{pdauthor}', '{pdauthoremail}', '{pdfiledate}', '{pddownloads}', '{pddescription}', '{pdfeatures}', '{pdchangelog}', '{pdnotes}', '{pdmirrorlink1}', '{pdmirrorlink2}', '{pdreportlink}', '{pdrating}', '{pdtags}', '{pdvideo}');
			break;
		}

		return $params;
	}
}
?>
