<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
/*
jimport('joomla.html.grid');
jimport('joomla.html.html.grid');
jimport('joomla.html.html.jgrid');
*/
defined('_JEXEC') or die();
if (! class_exists('JHtmlJGrid')) {
	require_once( JPATH_SITE.'/libraries/joomla/html/html/jgrid.php' );
}

class PhocaDownloadJGrid extends JHtmlJGrid
{
	
	public static function approved($value, $i, $prefix = '', $enabled = true, $checkbox='cb')
	{
		if (is_array($prefix)) {
			$options	= $prefix;
			$enabled	= array_key_exists('enabled',	$options) ? $options['enabled']		: $enabled;
			$checkbox	= array_key_exists('checkbox',	$options) ? $options['checkbox']	: $checkbox;
			$prefix		= array_key_exists('prefix',	$options) ? $options['prefix']		: '';
		}
		$states	= array(
			1	=> array('disapprove',	'COM_PHOCADOWNLOAD_APPROVED',	'COM_PHOCADOWNLOAD_NOT_APPROVE_ITEM',	'COM_PHOCADOWNLOAD_APPROVED',	false,	'publish',		'publish'),
			0	=> array('approve',		'COM_PHOCADOWNLOAD_NOT_APPROVED',	'COM_PHOCADOWNLOAD_APPROVE_ITEM',	'COM_PHOCADOWNLOAD_NOT_APPROVED',	false,	'unpublish',	'unpublish')
		);
		return self::state($states, $value, $i, $prefix, $enabled, true, $checkbox);
	}	
}
?>