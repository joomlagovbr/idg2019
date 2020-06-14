<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
class PhocaDownloadOrdering
{
	public static function getOrderingText ($ordering, $type = 1) {

		$pref = 'c';
		if ($type == 2) {
			$pref = 'cc';
		} else if ($type == 3) {
			$pref = 'a';
		}
		switch ((int)$ordering) {
			case 2:
				$orderingOutput	= 'ordering DESC';
			break;

			case 3:
				$orderingOutput	= 'title ASC';
			break;

			case 4:
				$orderingOutput	= 'title DESC';
			break;

			case 5:
				$orderingOutput	= 'date ASC';
			break;

			case 6:
				$orderingOutput	= 'date DESC';
			break;

			case 7:
				$orderingOutput	= 'id ASC';
			break;

			case 8:
				$orderingOutput	= 'id DESC';
			break;

			case 9:
				$orderingOutput	= 'hits ASC';
			break;

            case 10:
				$orderingOutput	= 'hits DESC';
			break;

			case 11:
				$orderingOutput	= 'filename ASC';
			break;

            case 12:
				$orderingOutput	= 'filename DESC';
			break;


			case 15:
				$orderingOutput 	= 'count ASC';
				$pref = 'r';
			break;
			case 16:
				$orderingOutput 	= 'count DESC';
				$pref = 'r';
			break;

			case 13:
				$orderingOutput 	= 'average ASC';
				$pref = 'r';
			break;
			case 14:
				$orderingOutput 	= 'average DESC';
				$pref = 'r';
			break;

			case 1:
			default:
				$orderingOutput = 'ordering ASC';
			break;
		}
		return $pref . '.' . $orderingOutput;
	}

	public static function renderOrderingFront( $selected, $type = 1) {

		switch($type) {
			case 2:
				$typeOrdering 	= PhocaDownloadOrdering::getOrderingCategoryArray();
				$ordering		= 'catordering';
			break;

			default:
				$typeOrdering 	= PhocaDownloadOrdering::getOrderingFileArray(1);
				$ordering		= 'fileordering';
			break;
		}

		$html 	= JHTML::_('select.genericlist',  $typeOrdering, $ordering, 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);

		return $html;
	}


	public static function getOrderingFileArray($frontend = 0) {

		$paramsC 	= JComponentHelper::getParams('com_phocadownload') ;

		if ($frontend == 1) {
			$ordering_asc_desc_arrows 	= $paramsC->get('ordering_asc_desc_arrows', 0);

			$item_ordering_values 		= $paramsC->get('file_ordering_values', '1,2,3,4,5,6,11,12,15,16,13,14,9,10');
		} else {
			$ordering_asc_desc_arrows 	= 0;
			$item_ordering_values 		= '1,2,3,4,5,6,7,8,11,12,15,16,13,14,9,10';
		}

		if ($ordering_asc_desc_arrows == 1) {


			$itemOrdering	= array(
				1 => JText::_('COM_PHOCADOWNLOAD_ORDERING') . " &nbsp;" . "&#8679;",
				2 => JText::_('COM_PHOCADOWNLOAD_ORDERING') . " &nbsp;" .  "&#8681;",
				3 => JText::_('COM_PHOCADOWNLOAD_TITLE') . " &nbsp;" . "&#8679;",
				4 => JText::_('COM_PHOCADOWNLOAD_TITLE') . " &nbsp;" .  "&#8681;",
				5 => JText::_('COM_PHOCADOWNLOAD_DATE') . " &nbsp;" . "&#8679;",
				6 => JText::_('COM_PHOCADOWNLOAD_DATE') . " &nbsp;" .  "&#8681;",
				//7 => JText::_('COM_PHOCADOWNLOAD_ID') . " &nbsp;" . "&#8679;",
				//8 => JText::_('COM_PHOCADOWNLOAD_ID') . " &nbsp;" .  "&#8681;",
				11 => JText::_('COM_PHOCADOWNLOAD_FILENAME') . " &nbsp;" . "&#8679;",
				12 => JText::_('COM_PHOCADOWNLOAD_FILENAME') . " &nbsp;" .  "&#8681;",

				15 => JText::_('COM_PHOCADOWNLOAD_COUNT') . " &nbsp;" . "&#8679;",
				16 => JText::_('COM_PHOCADOWNLOAD_COUNT') . " &nbsp;" .  "&#8681;",
				13 => JText::_('COM_PHOCADOWNLOAD_RATING') . " &nbsp;" . "&#8679;",
				14 => JText::_('COM_PHOCADOWNLOAD_RATING') . " &nbsp;" .  "&#8681;",
				9 => JText::_('COM_PHOCADOWNLOAD_DOWNLOADS') . " &nbsp;" . "&#8679;",
				10 => JText::_('COM_PHOCADOWNLOAD_DOWNLOADS') . " &nbsp;" .  "&#8681;");

		} else {


			$itemOrdering	= array(
				1 => JText::_('COM_PHOCADOWNLOAD_ORDERING_ASC'),
				2 => JText::_('COM_PHOCADOWNLOAD_ORDERING_DESC'),
				3 => JText::_('COM_PHOCADOWNLOAD_TITLE_ASC'),
				4 => JText::_('COM_PHOCADOWNLOAD_TITLE_DESC'),
				5 => JText::_('COM_PHOCADOWNLOAD_DATE_ASC'),
				6 => JText::_('COM_PHOCADOWNLOAD_DATE_DESC'),
				//7 => JText::_('COM_PHOCADOWNLOAD_ID_ASC'),
				//8 => JText::_('COM_PHOCADOWNLOAD_ID_DESC'),
				11 => JText::_('COM_PHOCADOWNLOAD_FILENAME_ASC'),
				12 => JText::_('COM_PHOCADOWNLOAD_FILENAME_DESC'),

				15 => JText::_('COM_PHOCADOWNLOAD_COUNT_ASC'),
				16 => JText::_('COM_PHOCADOWNLOAD_COUNT_DESC'),
				13 => JText::_('COM_PHOCADOWNLOAD_AVERAGE_ASC'),
				14 => JText::_('COM_PHOCADOWNLOAD_AVERAGE_DESC'),
				9 => JText::_('COM_PHOCADOWNLOAD_DOWNLOADS_ASC'),
				10 => JText::_('COM_PHOCADOWNLOAD_DOWNLOADS_DESC'));
		}

		$itemOrderingValuesA = explode(',', $item_ordering_values);

		//$itemOrdering = array_intersect_key($itemOrdering, $itemOrderingValues);
		$validItemOrdering = array();
		foreach ($itemOrderingValuesA as $k => $v) {
			if (isset($itemOrdering[$v])) {
				$validItemOrdering[$v] = $itemOrdering[$v];
			}
		}

		return $validItemOrdering;
	}



	public static function getOrderingCategoryArray() {
		$imgOrdering	= array(
				1 => JText::_('COM_PHOCADOWNLOAD_ORDERING_ASC'),
				2 => JText::_('COM_PHOCADOWNLOAD_ORDERING_DESC'),
				3 => JText::_('COM_PHOCADOWNLOAD_TITLE_ASC'),
				4 => JText::_('COM_PHOCADOWNLOAD_TITLE_DESC'),
				5 => JText::_('COM_PHOCADOWNLOAD_DATE_ASC'),
				6 => JText::_('COM_PHOCADOWNLOAD_DATE_DESC'),
				//7 => JText::_('COM_PHOCADOWNLOAD_ID_ASC'),
				//8 => JText::_('COM_PHOCADOWNLOAD_ID_DESC'),
				11 => JText::_('COM_PHOCADOWNLOAD_COUNT_ASC'),
				12 => JText::_('COM_PHOCADOWNLOAD_COUNT_DESC'),
				13 => JText::_('COM_PHOCADOWNLOAD_AVERAGE_ASC'),
				14 => JText::_('COM_PHOCADOWNLOAD_AVERAGE_DESC'),
				15 => JText::_('COM_PHOCADOWNLOAD_HITS_ASC'),
				16 => JText::_('COM_PHOCADOWNLOAD_HITS_DESC'));
		return $imgOrdering;
	}
}
?>
