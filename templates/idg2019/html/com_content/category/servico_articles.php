<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

// Create some shortcuts.
$params		= &$this->item->params;
$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>

	<?php if ($this->params->get('show_no_articles', 1)) : ?>
	<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
	<?php endif; ?>

<?php else : ?>
	<ul class="todas-noticias">
		<?php foreach ($this->items as $i => $article):
			$images = json_decode($article->images);
			?>
			<li>
          		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"><?php echo $article->title ?></a>
          		<?php if($article->metakey != ''): ?>
          		<div class="keywords">
                	tags:
                    <?php TemplateContentCategoryHelper::displayMetakeyLinks($article->metakey); ?>
                </div>
              	<?php endif; ?>
												
			</li>
			<!-- div.tileItem -->
		<?php endforeach; ?>
	</ul>
	<?php // Add pagination links ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div id="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<?php echo $this->pagination->getPagesCounter(); ?>
			<?php endif; ?>
			
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>