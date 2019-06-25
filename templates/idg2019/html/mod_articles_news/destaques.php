<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="container">
<div class="newsflash<?php echo $moduleclass_sfx; ?>">
	<h2><?php echo $module->title; ?></h2>
	


	<?php foreach ($list as $item) : ?>
		<?php require JModuleHelper::getLayoutPath('mod_articles_news', '_item'); ?>
	<?php endforeach; ?>

<?php 
var_dump($item->jcfields[3]->fieldparams->get('options')->options0)
	 ?>

</div>
</div>