<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_fields
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

if (!key_exists('field', $displayData))
{
	return;
}

$field = $displayData['field'];
$label = JText::_($field->label);
$value = $field->value;
$showLabel = $field->params->get('showlabel');
$labelClass = $field->params->get('label_render_class');

if ($value == '')
{
	return;
}

?>
<a class='btn btn-success' href=<?php echo $value; ?> target='_blank'><?php echo $label; ?></a>
