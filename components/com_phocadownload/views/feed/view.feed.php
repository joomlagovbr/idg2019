<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

class PhocaDownloadViewFeed extends JViewLegacy
{

	function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());
		//$db 		= JFactory::getDBO();
		//$menu		= $app->getMenu();
		$document	= JFactory::getDocument();
		//$params 	= $app->getParams();
		$moduleId	= $app->input->get('id', 0, 'int');
		//$table 		= JTable::getInstance('module');


		if ((int)$moduleId > 0) {
			$db = JFactory::getDBO();
			$query = 'SELECT a.params'
					. ' FROM #__modules AS a'
					. ' WHERE a.published = 1'
					. ' AND a.id ='.(int)$moduleId
					. ' ORDER BY a.ordering';

			$db->setQuery( $query );
			/*if (!$db->query()) {
				throw new Exception($db->getErrorMsg(), 500);
				return false;
			}*/
			$module = $db->loadObject();
			if (isset($module->params) && $module->params != '') {
				jimport( 'joomla.html.parameter' );
				$paramsM = new JRegistry;
				$paramsM->loadString($module->params);
				//$paramsM->loadJSON($module->params);

				// Params
				$categories 		= $paramsM->get( 'category_ids', '' );
				$ordering			= $paramsM->get( 'file_ordering', 6 );
				$fileCount			= $paramsM->get( 'file_count', 5 );
				$feedTitle			= $paramsM->get( 'feed_title', JText::_('COM_PHOCADOWNLOAD_DOWNLOAD') );
				$displayDateType	= $paramsM->get( 'display_date_type', 1 );

				$document->setTitle($this->escape( html_entity_decode($feedTitle)));

				$wheres = array();
				if (is_array($categories) && count($categories) > 0) {
					\Joomla\Utilities\ArrayHelper::toInteger($categories);
					$categoriesString	= implode(',', $categories);
					$wheres[]	= ' c.catid IN ( '.$categoriesString.' ) ';
				} else if ((int)$categories > 0) {
					$wheres[]	= ' c.catid IN ( '.$categories.' ) ';
				}

				$wheres[]	= ' c.catid= cc.id';
				$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND c.access IN ('.$userLevels.') ) )';
				$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND cc.access IN ('.$userLevels.') ) )';
				$wheres[] = ' c.published = 1';
				$wheres[] = ' c.approved = 1';
				$wheres[] = ' cc.published = 1';
				$wheres[] = ' c.textonly = 0';
				// Active
				$jnow		= JFactory::getDate();
				$now		= $jnow->toSql();
				$nullDate	= $db->getNullDate();
				$wheres[] = ' ( c.publish_up = '.$db->Quote($nullDate).' OR c.publish_up <= '.$db->Quote($now).' )';
				$wheres[] = ' ( c.publish_down = '.$db->Quote($nullDate).' OR c.publish_down >= '.$db->Quote($now).' )';
				$fileOrdering	= PhocaDownloadOrdering::getOrderingText($ordering);

				$query =  ' SELECT c.*, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias, cc.access as cataccess, cc.accessuserid as cataccessuserid '
						. ' FROM #__phocadownload AS c'
						. ' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = c.catid'
						. ' WHERE ' . implode( ' AND ', $wheres )
						. ' ORDER BY '.$fileOrdering;



				$db->setQuery( $query , 0, $fileCount );
				$files = $db->loadObjectList( );

				foreach ($files as $keyDoc => $valueDoc) {

					// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
					// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
					$rightDisplay	= 0;
					if (!empty($valueDoc)) {
						$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $valueDoc->cataccessuserid, $valueDoc->cataccess, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
					}
					// - - - - - - - - - - - - - - - - - - - - - -
					if ($rightDisplay == 1) {


						$item = new JFeedItem();

						$title 				= $this->escape( $valueDoc->title . ' ('.PhocaDownloadFile::getTitleFromFilenameWithExt( $valueDoc->filename ).')' );
						$title 				= html_entity_decode( $title );
						$item->title 		= $title;

						$link 				= PhocaDownloadRoute::getCategoryRoute($valueDoc->categoryid, $valueDoc->categoryalias);
						$item->link 		= JRoute::_($link);


						// FILEDATE
						$fileDate = '';
						if ((int)$displayDateType > 0) {
							if ($valueDoc->filename !='') {
								$fileDate = PhocaDownloadFile::getFileTime($valueDoc->filename, $displayDateType, "Y-m-d H:M:S");
							}
						} else {
							$fileDate = JHTML::Date($valueDoc->date, "Y-m-d H:i:s");
						}

						if ($fileDate != '') {
							$item->date			= $fileDate;
						}
						//$item->description 	= $valueDoc->description;
					//	$item->description 	= '<div><img src="media/com_phocadownload/images/phoca-download.png" alt="" /></div><div>New file "' .$valueDoc->title . '" ('. $valueDoc->filename.') released on '. $dateDesc.' is available on <a href="https://www.phoca.cz/download">Phoca download site</a></div>'.$valueDoc->description;

						$item->description 	= '<div><img src="media/com_phocadownload/images/phoca-download.png" alt="" /></div>'.$valueDoc->description;
						$item->category   	= $valueDoc->categorytitle;
					//	$item->section   	= $valueDoc->sectiontitle;
						if ($valueDoc->author != '') {
							$item->author		= $valueDoc->author;
						}

						$document->addItem( $item );
					}
				}
			}
		}
	}
}
?>
