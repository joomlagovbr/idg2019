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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Fields;

defined('_JEXEC') or die;

interface FieldInterface
{
	public function getAvailableFields();
}
