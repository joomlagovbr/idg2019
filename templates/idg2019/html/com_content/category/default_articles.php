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
				<span class="chapeu-noticia"><?php echo trim($article->xreference); ?></span>
          		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"><?php echo $article->title ?></a>
          		<div class="descricaoNoticia">
          			<?php  if (@isset($images->image_intro) && @!empty($images->image_intro) && @strpos($images->image_intro, 'www.youtube') === false) : ?>
					<div class="tileImage">
						<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
							<!-- <img
							<?php //if ($images->image_intro_caption):
								//echo ' title="' .htmlspecialchars($images->image_intro_caption) .'"';
							//endif; ?>
							class="tileImage" src="<?php //echo htmlspecialchars($images->image_intro); ?>" alt="<?php //echo htmlspecialchars($images->image_intro_alt); ?>" height="86" width="128" />	 -->						
						</a>
					</div>
					<?php endif; ?>
          			<?php echo TemplateContentCategoryHelper::getArticleIntro( $article ); ?>
					<?php if ($article->params->get('access-edit')) : ?>
					<ul class="actions">
						<li class="edit-icon">
							<?php echo JHtml::_('icon.edit', $article, $params); ?>
						</li>
					</ul>
					<?php endif; ?>
          		</div> 
          		<?php if($article->metakey != ''): ?>
          		<div class="keywords">
                	tags:
                    <?php TemplateContentCategoryHelper::displayMetakeyLinks($article->metakey); ?>
                </div>
              	<?php endif; ?>
				
				<span class="data-noticia">
					<?php echo JHtml::_('date', $article->publish_up, 'd/m/y'); ?> <?php echo JHtml::_('date', $article->publish_up, 'H\hi'); ?>
					<!--<ul>
						<?php
						// var_dump($article);
						//$author = $article->created_by_alias ? $article->created_by_alias : $article->author;
						?>
						 <li class="hide"><?php //echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?></li>
						<li class="hide"><?php //echo (($article->state == 1)? 'publicado' : 'n&atilde;o publicado' ) ?></li>
		
						<li><i class="icon-fixed-width icon-calendar"></i> </li>
						<li><i class="icon-fixed-width icon-time"></i> <?php //echo JHtml::_('date', $article->publish_up, 'H\hi'); ?></li>
						<li><i class="icon-fixed-width"></i> Artigo</li> 
					</ul>-->							            								
				</span>									
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