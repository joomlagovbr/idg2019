<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
class PhocaDownloadAccess
{
	public static function getCategoryAccess($id) {
		
		$output = array();
		$db 	= JFactory::getDBO();
		$query 	= 'SELECT c.access, c.uploaduserid, c.deleteuserid' .
				' FROM #__phocadownload_categories AS c' .
				' WHERE c.id = '. (int) $id .
				' ORDER BY c.id';
		$db->setQuery($query, 0, 1);
		$output = $db->loadObject();
		return $output;
	}
	
	public static function getCategoryAccessByFileId($id) {
		
		$output = array();
		$db 	= JFactory::getDBO();
		$query 	= 'SELECT c.access, c.uploaduserid, c.deleteuserid' .
				' FROM #__phocadownload_categories AS c' .
				' LEFT JOIN #__phocadownload as a ON a.catid = c.id' .
				' WHERE a.id = '. (int) $id .
				' ORDER BY c.id';
		$db->setQuery($query, 0, 1);
		$output = $db->loadObject();
		return $output;
	}
	

	
	/**
	 * Method to check if the user have access to category
	 * Display or hide the not accessible categories - subcat folder will be not displayed
	 * Check whether category access level allows access
	 *
	 * E.g.: Should the link to Subcategory or to Parentcategory be displayed
	 * E.g.: Should the delete button displayed, should be the upload button displayed
	 *
	 * @param string $params rightType: accessuserid, uploaduserid, deleteuserid - access, upload, delete right
	 * @param int $params rightUsers - All selected users which should have the "rightType" right
	 * @param int $params rightGroup - All selected Groups of users(public, registered or special ) which should have the "rT" right
	 * @param int $params userAID - Specific group of user who display the category in front (public, special, registerd)
	 * @param int $params userId - Specific id of user who display the category in front (1,2,3,...)
	 * @param int $params Additional param - e.g. $display_access_category (Should be unaccessed category displayed)
	 * @return boolean 1 or 0
	 * $rightUsers -> $userId
	 * $rightGroup -> $userAID
	 */
	 
	
	 
	 public static function getUserRight($rightType = 'accessuserid', $rightUsers, $rightGroup = 0, $userAID = array(), $userId = 0 , $additionalParam = 0 ) {	
	 
		$user = JFactory::getUser();
		// we can get the variables here, not before function call
		$userAID = $user->getAuthorisedViewLevels();
		$userId = $user->get('id', 0);
		
		$guest = 0;
		if (isset($user->guest) && $user->guest == 1) {
			$guest = 1;
		}
		
		// User ACL
		$rightGroupAccess = 0;
		// User can be assigned to different groups
		foreach ($userAID as $keyUserAID => $valueUserAID) {
			if ((int)$rightGroup == (int)$valueUserAID) {
				$rightGroupAccess = 1;
				break;
			}
		}
		;
		
		$rightUsersIdArray = array();
		if (!empty($rightUsers)) {
			$rightUsersIdArray = explode( ',', trim( $rightUsers ) );
		} else {
			$rightUsersIdArray = array();
		}

		$rightDisplay = 1;
		if ($additionalParam == 0) { // We want not to display unaccessable categories ($display_access_category)
			if ($rightGroup != 0) {
			
				if ($rightGroupAccess == 0) {
					$rightDisplay  = 0;
				} else { // Access level only for one registered user
					if (!empty($rightUsersIdArray)) {
						// Check if the user is contained in selected array
						$userIsContained = 0;
						foreach ($rightUsersIdArray as $key => $value) {
							if ($userId == $value) {
								$userIsContained = 1;// check if the user id is selected in multiple box
								break;// don't search again
							}
							// for access (-1 not selected - all registered, 0 all users)
							if ($value == -1) {
								if ($guest == 0) {
									$userIsContained = 1;// in multiple select box is selected - All registered users
								}
								break;// don't search again
							}
						}

						if ($userIsContained == 0) {
							$rightDisplay = 0;
						}
					} else {
						
						// Access rights (Default open for all)
						// Upload and Delete rights (Default closed for all)
						switch ($rightType) {
							case 'accessuserid':
								$rightDisplay = 1;
							break;
							
							Default:
								$rightDisplay = 0;
							break;
						}
					}
				}	
			}
		}
		return $rightDisplay;
	}

	
	/*
	 *
	 */
	public static function getNeededAccessLevels() {
	
		$paramsC 				= JComponentHelper::getParams('com_phocadownload');
		$registeredAccessLevel 	= $paramsC->get( 'registered_access_level', array(2,3,4) );
		return $registeredAccessLevel;
	}
	
	/*
	 * Check if user's groups access rights (e.g. user is public, registered, special) can meet needed Levels
	 */
	
	public static function isAccess($userLevels, $neededLevels) {
		
		$rightGroupAccess = 0;
		
		// User can be assigned to different groups
		foreach($userLevels as $keyuserLevels => $valueuserLevels) {
			foreach($neededLevels as $keyneededLevels => $valueneededLevels) {
			
				if ((int)$valueneededLevels == (int)$valueuserLevels) {
					$rightGroupAccess = 1;
					break;
				}
			}
			if ($rightGroupAccess == 1) {
				break;
			}
		}
		return (boolean)$rightGroupAccess;
	}
}
?>