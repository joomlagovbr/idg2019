<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('formbehavior.chosen', 'select');

$n = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>


<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
    <?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
        <fieldset class="filters btn-toolbar">
            <?php if ($this->params->get('filter_field')) : ?>
                <div class="btn-group">
                    <label class="filter-search-lbl element-invisible" for="filter-search">
                        <?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?>
                    </label>
                    <input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>" />
                </div>
            <?php endif; ?>
            <?php if ($this->params->get('show_pagination_limit')) : ?>
                <div class="btn-group pull-right">
                    <label for="limit" class="element-invisible">
                        <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
                    </label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            <?php endif; ?>

            <input type="hidden" name="filter_order" value="" />
            <input type="hidden" name="filter_order_Dir" value="" />
            <input type="hidden" name="limitstart" value="" />
            <input type="hidden" name="task" value="" />
            <div class="clearfix"></div>
        </fieldset>
    <?php endif; ?>

    <?php if ($this->items == false || $n == 0) : ?>
        <p> <?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p></div>
<?php else : ?>

    <ul class="todas-noticias">
        <?php foreach ($this->items as $i => $item) : ?>
            <?php
            $itemDate = array(
                'published' => $item->core_publish_up,
                'created' => $item->core_created_time,
                'modified' => $item->core_modified_time
            );
            ?>

            <li class="tileItem">
                <?php
                $images = json_decode($item->core_images);
                $metadata = json_decode($item->core_metadata);
                ?>

                <!-- <div class="span10 tileContent">
                    <?php if (@isset($images->image_intro) && @!empty($images->image_intro) && @strpos($images->image_intro, 'www.youtube') === false) : ?>
                        <div class="tileImage">
                            <a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
                                <img
                                <?php
                                if ($images->image_intro_caption):
                                    echo ' title="' . htmlspecialchars($images->image_intro_caption) . '"';
                                endif;
                                ?>
                                    class="tileImage" src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" height="86" width="128" />							
                            </a>
                        </div>
                    <?php endif; ?> -->
                    <span class="subtitle"><?php echo trim($metadata->xreference); ?></span>
                    <!-- <h2 class="tileHeadline"> -->
                        <a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
                            <?php echo $item->core_title; ?>
                        </a>
                    <!-- </h2> -->
                    <div class="descricaoNoticia">
                            <?php echo substr(strip_tags($item->core_body), 0, 170); ?>...
                        </div>
               <!--  </div> -->
                <!-- <div class="span2 tileInfo">
                    <ul>

                        <?php if ($this->params->get('tag_list_show_date')) : ?>
                            <li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $itemDate[$this->params->get('tag_list_show_date')], 'd/m/y'); ?></li>
                            <li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $itemDate[$this->params->get('tag_list_show_date')], 'H\hi'); ?></li>
                        <?php endif; ?>
                    </ul>
                </div> -->
            </li>
        <?php endforeach; ?>
    </ul>
    </div>
<?php endif; ?>

<?php // Add pagination links  ?>
<?php if (!empty($this->items)) : ?>
    <?php if (($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
        <div class="pagination">

            <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                <p class="counter pull-right">
                    <?php echo $this->pagination->getPagesCounter(); ?>
                </p>
            <?php endif; ?>

            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
</form>