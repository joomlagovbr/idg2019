<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<ul class="relateditems mod-list">
<?php foreach ($list as $item) : ?>
<li>
	<div class="data"><?php if ($showDate) echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?></div>
	<a href="<?php echo $item->route; ?>" title="<?php echo $item->title; ?>"> <?php echo $item->title; ?></a>
</li>
<?php endforeach; ?>
<div class="clear"></div>
</ul>
