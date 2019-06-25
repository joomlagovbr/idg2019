<?php
/**
 * @version    2.7.x
 * @package    K2
 * @author     JoomlaWorks http://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2016 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

$AliasExtraFields = array();
foreach ($this->item->extra_fields as $key=>$extraField):
  $AliasExtraFields[ $extraField->alias ] = $extraField;
endforeach;

?>
<div class="parceiroInterna">
  <div class="container">
    <div class="row detalheParceiro">

  <?php if($this->item->params->get('itemTitle')): ?>
  <!-- Item title -->
  <h2>
    <?php if(isset($this->item->editLink)): ?>
    <!-- Item edit link -->
    <span class="itemEditLink">
      <a data-k2-modal="edit" href="<?php echo $this->item->editLink; ?>"><?php echo JText::_('K2_EDIT_ITEM'); ?></a>
    </span>
    <?php endif; ?>

    <?php echo $this->item->title; ?>

    <?php if($this->item->params->get('itemFeaturedNotice') && $this->item->featured): ?>
    <!-- Featured flag -->
    <span>
      <sup>
        <?php echo JText::_('K2_FEATURED'); ?>
      </sup>
    </span>
    <?php endif; ?>
  </h2>
  <?php endif; ?>

  <div>

    <?php if($this->item->params->get('itemImage') && !empty($this->item->image)): ?>
    <!-- Item Image -->
    <div class="itemImageBlock">
      <span class="itemImage">
        <a data-k2-modal="image" href="<?php echo $this->item->imageXLarge; ?>" title="<?php echo JText::_('K2_CLICK_TO_PREVIEW_IMAGE'); ?>">
          <img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
        </a>
      </span>

      <?php if($this->item->params->get('itemImageMainCaption') && !empty($this->item->image_caption)): ?>
      <!-- Image caption -->
      <span class="itemImageCaption"><?php echo $this->item->image_caption; ?></span>
      <?php endif; ?>

      <?php if($this->item->params->get('itemImageMainCredits') && !empty($this->item->image_credits)): ?>
      <!-- Image credits -->
      <span class="itemImageCredits"><?php echo $this->item->image_credits; ?></span>
      <?php endif; ?>

      <div class="clr"></div>
    </div>
    <?php endif; ?>

    <?php if(!empty($this->item->fulltext)): ?>

    <?php if($this->item->params->get('itemIntroText')): ?>
    <!-- Item introtext -->
    <div class="itemIntroText">
      <?php echo $this->item->introtext; ?>
    </div>
    <?php endif; ?>

    <?php if($this->item->params->get('itemFullText')): ?>
    <!-- Item fulltext -->
    <div class="itemFullText">
      <?php echo $this->item->fulltext; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>

    <!-- Item text -->
    <div class="itemFullText">
      <?php echo $this->item->introtext; ?>
    </div>

    <?php endif; ?>

    <div class="clr"></div>

    <?php if($this->item->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
    <!-- Item extra fields -->
    <div class="itemExtraFields">
      <h3><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h3>
      <ul>
        <?php foreach ($this->item->extra_fields as $key=>$extraField): ?>
        <?php if($extraField->value != ''): ?>
        <li class="<?php echo ($key%2) ? "odd" : "even"; ?> type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?>">
          <?php if($extraField->type == 'header'): ?>
          <h4 class="itemExtraFieldsHeader"><?php echo $extraField->name; ?></h4>
          <?php else: ?>
          <span class="itemExtraFieldsLabel"><?php echo $extraField->name; ?>:</span>
          <span class="itemExtraFieldsValue"><?php echo $extraField->value; ?></span>
          <?php endif; ?>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>
      </ul>
      <div class="clr"></div>
    </div>
    <?php endif; ?>

    <div class="clr"></div>

  </div>

  <div class="clr"></div>

  <?php if($this->item->params->get('itemVideo') && !empty($this->item->video)): ?>
  <!-- Item video -->
  <a name="itemVideoAnchor" id="itemVideoAnchor"></a>
  <div class="itemVideoBlock">
    <h3><?php echo JText::_('K2_MEDIA'); ?></h3>

    <?php if($this->item->videoType=='embedded'): ?>
    <div class="itemVideoEmbedded">
      <?php echo $this->item->video; ?>
    </div>
    <?php else: ?>
    <span class="itemVideo"><?php echo $this->item->video; ?></span>
    <?php endif; ?>

    <?php if($this->item->params->get('itemVideoCaption') && !empty($this->item->video_caption)): ?>
    <span class="itemVideoCaption"><?php echo $this->item->video_caption; ?></span>
    <?php endif; ?>

    <?php if($this->item->params->get('itemVideoCredits') && !empty($this->item->video_credits)): ?>
    <span class="itemVideoCredits"><?php echo $this->item->video_credits; ?></span>
    <?php endif; ?>

    <div class="clr"></div>
  </div>
  <?php endif; ?>

  <?php if($this->item->params->get('itemImageGallery') && !empty($this->item->gallery)): ?>
  <!-- Item image gallery -->
  <a name="itemImageGalleryAnchor" id="itemImageGalleryAnchor"></a>
  <div class="itemImageGallery">
    <h3><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h3>
    <?php echo $this->item->gallery; ?>
  </div>
  <?php endif; ?>

  <div class="clr"></div>

<!-- End K2 Item Layout -->

    </div> <!-- CONTEUDO -->
  </div>
</div>
