<?php
/**
 * @package         Regular Labs Library
 * @version         20.3.22179
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

/**
 * Class ObjectHelper
 * @package RegularLabs\Library
 */
class ObjectHelper
{
	/**
	 * Return the value by the object property key
	 * A list of keys can be given. The first one that is not empty will get returned
	 *
	 * @param object       $object
	 * @param string|array $keys
	 *
	 * @return mixed
	 */
	public static function getValue($object, $keys, $default = null)
	{
		$keys = ArrayHelper::toArray($keys);

		foreach ($keys as $key)
		{
			if (empty($object->{$key}))
			{
				continue;
			}

			return $object->{$key};
		}

		return $default;
	}
}
