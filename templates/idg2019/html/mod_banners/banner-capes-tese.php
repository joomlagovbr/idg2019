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

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
  <?php 
    $controle_data_slide = 0;
    foreach ($list as $key => $item) :
    ?>
    <?php if ($key == 0) { ?>
      <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <?php }else{ ?>
      <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $controle_data_slide ?>"></li>
    <?php } 
    $controle_data_slide++;  
    endforeach; 
  ?>
  </ol>
  <div class="carousel-inner">
  <?php 
            foreach ($list as $key => $item) :
            ?>
            <?php if ($key == 0) { ?>
                <div class="carousel-item active">
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
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
    <!---->
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
