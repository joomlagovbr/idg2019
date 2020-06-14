<?php

/**
 * copyright (C) 2015 GWE Systems Ltd - All rights reserved
 * @license GNU/GPLv3 www.gnu.org/licenses/gpl-3.0.html
 * */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// This will only be triggered in Joomla 3.4.0 or later after a change in the name convention of file packages
class ptBR_JEventsInstallerScript
{

	private $oldelement = "BrazilianPortugueseTranslationforJEvents";
	private $newelement = "pt-BR_JEvents";
	//
	// Joomla installer functions
	//
	public function preflight($type, $parent)
	{
	}

	function postflight($type, $parent)
	{

		// Joomla! broke the update call, so we have to create a workaround check.
		$db = JFactory::getDbo();
		$db->setQuery("SELECT * FROM #__extensions WHERE element =".$db->quote($this->oldelement). " OR element=".$db->quote($this->newelement));
		$extensions = $db->loadObjectList();

		if (count($extensions)>1){
			$hasold = false;
			$hasnew = false;
			foreach ($extensions as $extension){
				if ( strtolower($extension->element) == strtolower($this->oldelement) )
				{
					$hasold = $extension;
				}
				else if ( strtolower($extension->element) == strtolower($this->newelement) )
				{
					$hasnew = $extension ;
				}
			}
			if ($hasold && $hasnew){
				$db->setQuery("DELETE FROM #__extensions WHERE element =".$db->quote($this->oldelement));
				$db->query();
			}
		}
		return;
	}

}
