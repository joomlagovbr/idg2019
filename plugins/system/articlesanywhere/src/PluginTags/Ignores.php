<?php
/**
 * @package         Articles Anywhere
 * @version         10.1.4
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\PluginTags;

defined('_JEXEC') or die;

class Ignores
{
	protected $component;

	public function __construct($component)
	{
		$this->component = $component;
	}

	public function get(&$attributes)
	{
		$ignores      = [];
		$ignore_types = ['language', 'state', 'access'];

		foreach ($attributes as $attribute_key => $value)
		{
			if (strpos($attribute_key, 'ignore_') !== 0)
			{
				continue;
			}

			// strip off the 'ignore_' prefix
			$key  = substr($attribute_key, 7);
			$type = strtok($key, '_');

			if ( ! in_array($type, $ignore_types))
			{
				continue;
			}

			$ignores[$key] = $value;
			unset($attributes->{$attribute_key});
		}

		return $ignores;
	}
}
