<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//JLoader::register('BannerHelper', JPATH_ROOT . '/components/com_banners/helpers/banner.php');
?>



<div id="<?php echo $params->get('moduleclass_sfx') ?>" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <?php             
			$i = 0;
			$active = "active";
			foreach ($list as $li) : 
		?>
				<li data-target="#<?php echo $params->get('moduleclass_sfx') ?>" data-slide-to="<?php echo $i; ?>" class="<?php echo $active; ?>"></li>
		<?php  
			$i++; 
			$active = '';       
			endforeach; 
		?>
    </ol>
    <div class="carousel-inner">
    	<?php 
            $active = 'active';
            foreach ($list as $key => $item) :
            ?>
            <?php if ($key == 0) { ?>
                <div class="carousel-item <?php echo $active; ?>">
            <?php }else{ ?>
                <div class="carousel-item">
            <?php } ?>
                    <div>
                        <a href="<?php echo $item->clickurl; ?>" title="<?php echo $item->params->get('alt') ?>" /><img src="<?php echo $item->params->get('imageurl') ?>" alt="<?php echo $item->params->get('alt') ?>" /></a>
                    </div>
                </div>
            <?php  
            endforeach; 
        ?>
	</div>

    <a class="carousel-control-prev" href="#<?php echo $params->get('moduleclass_sfx') ?>" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#<?php echo $params->get('moduleclass_sfx') ?>" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
