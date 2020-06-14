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

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

/**
 * Class MijoshopPagetype
 * @package RegularLabs\Library\Condition
 */
class MijoshopPagetype
	extends Mijoshop
{
	public function pass()
	{
		return $this->passByPageType('com_mijoshop', $this->selection, $this->include_type, true);
	}
}
