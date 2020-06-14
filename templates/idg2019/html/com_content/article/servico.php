<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access

defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

require __DIR__.'/_helper.php';
$category_alias_layout = TemplateContentArticleHelper::getTemplateByCategoryAlias( $this->item );

//modificacoes relativas � publica��o no facebook
$current_url = JURI::getInstance()->toString();
$this->document->addCustomTag('<meta property="og:url" content="'. $current_url .'" />');
$this->document->addCustomTag('<meta property="og:type" content="article" />');
$this->document->addCustomTag('<meta property="og:title" content="'. $this->escape($this->item->category_title) .' - '. $this->escape($this->item->title) .'" />');

//alteração para pegar imagens do introimages do artigo.
//$img_tmb = TemplateContentArticleHelper::customImg($this->item->text);
$img_tmb = TemplateContentArticleHelper::customImg(json_decode($this->item->images)->image_intro);
JFactory::getDocument()->addCustomTag('<meta property="og:image" content="'.$img_tmb.'" />');

//pega custom fields
foreach($this->item->jcfields as $jcfield)
{
    $this->item->jcFields[$jcfield->name] = $jcfield;
}

// Create shortcuts to some parameters.
$params = $this->item->params;
$images = json_decode($this->item->images);
$urls = json_decode($this->item->urls);
$canEdit = $this->item->params->get('access-edit');
$user = JFactory::getUser();
$doc     = JFactory::getDocument();

$doc->setMetaData( 'date_published', JHtml::date($this->item->publish_up, 'Y-m-d') );

if ($this->item->catid <= 2)
    $params->set('show_category', 0);
?>

    <?php if (@$this->item->xreference != '') : ?>
        <span class="chapeu"><?php echo $this->escape($this->item->xreference); ?></span>
    <?php elseif ($this->params->get('show_page_heading')) : ?>
        <span class="chapeu"><?php echo $this->escape($this->params->get('page_heading')); ?></span>
    <?php endif; ?>
    <?php if ($params->get('show_title')) : ?>
        <h2>
            <?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
                <a href="<?php echo $this->item->readmore_link; ?>">
                    <?php echo $this->escape($this->item->title); ?></a>
            <?php else : ?>
                <?php echo $this->escape($this->item->title); ?>
            <?php endif; ?>
        </h2>
    <?php endif; ?>

    <?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
        <ul class="actions">
            <?php if (!$this->print) : ?>
                <?php if ($canEdit) : ?>
                    <li class="edit-icon">
                        <?php echo JHtml::_('icon.edit', $this->item, $params); ?>
                    </li>
                <?php endif; ?>
            <?php else : ?>
                <li>
                    <?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
                </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <?php if (!$params->get('show_intro')) : ?>
        <div class="subtitulo-noticia">
            <?php echo $this->item->event->afterDisplayTitle; ?>
        </div>
    <?php endif; ?>

    <?php
    $menuModule = JModuleHelper::getModules('com_content-article-menu');
    if (count($menuModule)):
        ?>
        <?php foreach ($menuModule as $module): ?>
            <?php $html = JModuleHelper::renderModule($module); ?>
            <?php $html = str_replace('{SITE}', JURI::root(), $html); ?>
            <?php echo $html; ?>
        <?php endforeach; ?>
        <?php
    endif;
    ?>

    <?php echo $this->item->event->beforeDisplayContent; ?>

    <!-- fim .content-header-options-1 -->


    <?php if (isset($this->item->toc)) : ?>
        <?php echo $this->item->toc; ?>
    <?php endif; ?>

    <?php if (isset($urls) AND ( $params->get('urls_position') == '0')): ?>
        <?php if ($urls->urla || $urls->urlb || $urls->urlc): ?>
            <blockquote>
                <p>Links relacionados:</p>
                <?php echo $this->loadTemplate('links'); ?>
            </blockquote>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($params->get('access-view')): ?>
        <?php
        if (!empty($this->item->pagination) AND $this->item->pagination AND ! $this->item->paginationposition AND ! $this->item->paginationrelative):
            echo $this->item->pagination;
        endif;
        ?>
        <?php if ($this->item->fulltext != null && $this->item->fulltext != ''): ?>
            <div class="description">
                <?php echo $this->item->introtext; ?>
            </div>
            <?php TemplateContentArticleHelper::displayFulltextImage($images, $params); ?>
            <?php if ($params->get('show_readmore')) : ?>
                <?php echo $this->item->fulltext; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php TemplateContentArticleHelper::displayFulltextImage($images, $params); ?>
            <?php echo $this->item->text; ?>
        <?php endif; ?>
        <?php
        if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND ! $this->item->paginationrelative):
            echo $this->item->pagination;
            ?>
        <?php endif; ?>

        <?php if (isset($urls) AND ( (!empty($urls->urls_position) AND ( $urls->urls_position == '1')) OR ( $params->get('urls_position') == '1') )): ?>
            <?php echo $this->loadTemplate('links'); ?>
        <?php endif; ?>
        <?php //optional teaser intro text for guests  ?>
    <?php elseif ($params->get('show_noauth') == true and $user->get('guest')) : ?>
        <div class="description">
            <?php echo $this->item->introtext; ?>
        </div>
        <?php //Optional link to let them register to see the whole article.  ?>
        <?php
        if ($params->get('show_readmore') && $this->item->fulltext != null) :
            $link1 = JRoute::_('index.php?option=com_users&view=login');
            $link = new JURI($link1);
            ?>
            <p class="readmore">
                <a href="<?php echo $link; ?>">
                    <?php $attribs = json_decode($this->item->attribs); ?>
                    <?php
                    if ($attribs->alternative_readmore == null) :
                        echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
                    elseif ($readmore = $this->item->alternative_readmore) :
                        echo $readmore;
                        if ($params->get('show_readmore_title', 0) != 0) :
                            echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                        endif;
                    elseif ($params->get('show_readmore_title', 0) == 0) :
                        echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
                    else :
                        echo JText::_('COM_CONTENT_READ_MORE');
                        echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                    endif;
                    ?></a>
            </p>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND $this->item->paginationrelative):
        echo $this->item->pagination;
        ?>
    <?php endif; ?>

    <?php echo $this->item->event->afterDisplayContent; ?>

<?php
$articleFooterModule = JModuleHelper::getModules($this->params['pageclass_sfx'] . '-article-footer');
if (count($articleFooterModule)):
    ?>
    <?php foreach ($articleFooterModule as $module): ?>
        <?php $html = JModuleHelper::renderModule($module); ?>
        <?php $html = str_replace('{SITE}', JURI::root(), $html); ?>
        <?php echo $html; ?>
    <?php endforeach; ?>
    <?php
endif;
?>
<?php
$categories = TemplateContentArticleHelper::getParentCategoriesByRoute($this->item->parent_route);
$showBelowContent = TemplateContentArticleHelper::showBelowContent($categories, $this->item);

// gato para retirar os botóes de categoria e tags
//$showBelowContent = [];
if (count($showBelowContent) > 0):
    ?>
    <div class="below-content">
       <!--  <?php if (in_array('categories', $showBelowContent)): ?>
            <div class="line">
                registrado em:
                <?php TemplateContentArticleHelper::displayCategoryLinks($categories, $this->item); ?>
            </div>
        <?php endif; ?> -->

        <?php if (in_array('metakeys', $showBelowContent)): ?>
            <div class="line keys">
                Tags: <?php TemplateContentArticleHelper::displayMetakeyLinks($this->item->metakey); ?>		
            </div>
        <?php endif; ?>

        <?php if (isset($urls) AND $params->get('urls_position') != '0'): ?>
            <?php if ($urls->urla || $urls->urlb || $urls->urlc): ?>
                <div class="line">
                    <h3>link(s) relacionado(s):	</h3>
                    <?php echo $this->loadTemplate('links'); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
<?php endif; ?>

