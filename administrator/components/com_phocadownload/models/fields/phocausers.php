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
phocadownloadimport('phocadownload.user.user');


class JFormFieldPhocaUsers extends JFormField
{
	protected $type 		= 'PhocaUsers';

	protected function getInput() {
		
		$userId	= (string) $this->form->getValue($this->element['name']);	
		
		return PhocaDownloadUser::usersList($this->name, $this->id, $userId, 1, NULL,'name', 0 );
		
		
	}
}
?>