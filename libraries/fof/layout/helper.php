<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  layout
 * @copyright   Copyright (C) 2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @note        This file has been modified by the Joomla! Project and no longer reflects the original work of its author.
 */
// Protect from unauthorized access
defined('FOF_INCLUDED') or die;

/**
 * Helper to render a FOFLayout object, storing a base path
 *
 * @package  FrameworkOnFramework
 * @since    x.y
 */
class FOFLayoutHelper extends JLayoutHelper
{
	/**
	 * Method to render the layout.
	 *
	 * @param   string  $layoutFile   Dot separated path to the layout file, relative to base path
	 * @param   object  $displayData  Object which properties are used inside the layout file to build displayed output
	 * @param   string  $basePath     Base path to use when loading layout files
	 * @param   mixed   $options      Optional custom options to load. Registry or array format
	 *
	 * @return  string
	 */
	public static function render($layoutFile, $displayData = null, $basePath = '', $options = null)
	{
		$basePath = empty($basePath) ? self::$defaultBasePath : $basePath;

		// Make sure we send null to FOFLayoutFile if no path set
		$basePath = empty($basePath) ? null : $basePath;
		$layout = new FOFLayoutFile($layoutFile, $basePath, $options);
		$renderedLayout = $layout->render($displayData);

		return $renderedLayout;
	}
}
