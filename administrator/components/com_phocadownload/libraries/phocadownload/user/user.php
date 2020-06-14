<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class PhocaDownloadUser
{
	public static function getUserLang( $formName = 'language') {
		$user 		= JFactory::getUser();
		$paramsC 	= JComponentHelper::getParams('com_phocadownload') ;
		$userLang	= $paramsC->get( 'user_ucp_lang', 1 );
		
		$o = array();
		
		switch ($userLang){
			case 2:
				$registry = new JRegistry;
				$registry->loadString($user->params);
				$o['lang'] 		= $registry->get('language','*');
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="'.$o['lang'].'" />';
			break;
			
			case 3:
				$o['lang'] 		= JFactory::getLanguage()->getTag();
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="'.$o['lang'].'" />';
			break;
			
			default:
			case 1:
				$o['lang'] 		= '*';
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="*" />';
			break;
		}
		return $o;
	}
	
	public static function getUserFileInfo($file, $userId) {		
		
		$db 				= JFactory::getDBO();
		$allFile['size']	= 0;
		$allFile['count']	= 0;
		$query = 'SELECT SUM(a.filesize) AS sumfiles, COUNT(a.id) AS countfiles'
				.' FROM #__phocadownload AS a'
			    .' WHERE a.owner_id = '.(int)$userId;
		$db->setQuery($query, 0, 1);
		$fileData = $db->loadObject();
		
		if(isset($fileData->sumfiles) && (int)$fileData->sumfiles > 0) {
			$allFile['size'] = (int)$allFile['size'] + (int)$fileData->sumfiles;
		}
		
		if (isset($file['size'])) {
				$allFile['size'] = (int)$allFile['size'] + (int)$file['size'];
				$allFile['count'] = (int)$fileData->countfiles + 1;
		}
		
		return $allFile;
	}
	
	/**
	 * Method to display multiple select box
	 * @param string $name Name (id, name parameters)
	 * @param array $active Array of items which will be selected
	 * @param int $nouser Select no user
	 * @param string $javascript Add javascript to the select box
	 * @param string $order Ordering of items
	 * @param int $reg Only registered users
	 * @return array of id
	 */
	
	public static function usersList( $name, $id, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 ) {
		
		$activeArray = $active;
		if ($active != '') {
			$activeArray = explode(',',$active);
		}
		
		$db		= JFactory::getDBO();
		$and 	= '';
		if ($reg) {
			// does not include registered users in the list
			$and = ' AND m.group_id != 2';
		}

		$query = 'SELECT u.id AS value, u.name AS text'
		. ' FROM #__users AS u'
		. ' JOIN #__user_usergroup_map AS m ON m.user_id = u.id'
		. ' WHERE u.block = 0'
		. $and
		. ' GROUP BY u.id'
		. ' ORDER BY '. $order;
		
		
		
		$db->setQuery( $query );
		if ( $nouser ) {
			
			// Access rights (Default open for all)
			// Upload and Delete rights (Default closed for all)
			switch ($name) {
				case 'jform[accessuserid][]':
					$idInput1 	= -1;
					$idText1	= JText::_( 'COM_PHOCADOWNLOAD_ALL_REGISTERED_USERS' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'COM_PHOCADOWNLOAD_NOBODY' );
				break;
				
				Default:
					$idInput1 	= -2;
					$idText1	= JText::_( 'COM_PHOCADOWNLOAD_NOBODY' );
					$idInput2 	= -1;
					$idText2	= JText::_( 'COM_PHOCADOWNLOAD_ALL_REGISTERED_USERS' );
				break;
			}
			
			$users[] = JHTML::_('select.option',  $idInput1, '- '. $idText1 .' -' );
			$users[] = JHTML::_('select.option',  $idInput2, '- '. $idText2 .' -' );
			
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTML::_('select.genericlist', $users, $name, 'class="inputbox" size="4" multiple="multiple"'. $javascript, 'value', 'text', $activeArray, $id );

		return $users;
	}
}
?>