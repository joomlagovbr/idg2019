<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('BannerHelper', JPATH_ROOT . '/components/com_banners/helpers/banner.php');
?>


<div class="container">
    
    <div class="swiper-container participacao-social">
        <div class="swiper-wrapper">

            <?php 
            foreach ($list as $key => $item) :
            ?>

            <div class="swiper-slide">
                <a href="<?php echo $item->clickurl; ?>" title="<?php echo $item->name; ?>">
                <img src="<?php echo $item->params->get('imageurl') ?>" alt="<?php echo $item->name; ?>">
                <span><?php echo $item->name; ?></span>

                <?php echo $item->description; ?>
                </a>
            </div>

            <?php endforeach;  ?>
            
        </div>

        <!-- Add Pagination -->
        <div class="swiper-pagination navegacao-participacao"></div>

        <!-- Add Arrows -->
        <div class="swiper-button-next proximo-participacao"></div>
        <div class="swiper-button-prev anterior-participacao"></div>
    </div>

</div>

<br><br><br>

<script>
    var swiperParticipacaoSocial = new Swiper('.participacao-social', {
        slidesPerView: 4,
        pagination: {
            el: '.navegacao-participacao',
            clickable: true,
        },
        navigation: {
            nextEl: '.proximo-participacao',
            prevEl: '.anterior-participacao',
        },
    });
</script>