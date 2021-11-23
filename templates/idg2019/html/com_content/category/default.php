<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
require __DIR__.'/_helper.php';

?>
<!-- <div class="category-list<?php echo $this->pageclass_sfx;?>">
 -->
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h2>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h2>
	<?php else : ?>
	<h2>		
		<?php echo $this->category->title; ?>
	</h2>
	<?php endif; ?>

	<?php if( $this->params->get('list_show_author') || $this->params->get('list_show_date')) : ?>
		<div class="content-header-options-1 row-fluid">
			<div class="documentByLine">
				<?php if( $this->params->get('list_show_author') ): ?>
				<span class="documentAuthor">
					<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '<strong>'.TemplateContentCategoryHelper::getAuthor( $this->category ).'</strong>'); ?>
				</span>
				<?php endif; ?>
				<?php if( $this->params->get('list_show_date') && $this->params->get('list_show_author')): ?>
				<span class="separator">|</span>
				<?php endif; ?>
				<?php if( $this->params->get('list_show_date') ): ?>
				<?php
				$created =  JHtml::_('date', $this->category->created_time, JText::_('DATE_FORMAT_LC2'));
				?>
				<span class="documentCreated"><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', $created); ?></span>
				<span class="separator">|</span>
				<?php 
				$modified = TemplateContentCategoryHelper::getLastArticleModifiedDate( $this->category, $this->children );
				?>
				<span class="documentModified"><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', $modified); ?></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if($this->params->get('page_subheading')): ?>
		<h2 class="secondaryHeading"><?php echo $this->escape($this->params->get('page_subheading')); ?></h2>
	<?php endif; ?>

	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="subtitle">
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<?php TemplateContentCategoryHelper::displayCategoryImage( $this->category->getParams()->get('image') ); ?>
		<?php endif; ?>
		<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
		<?php endif; ?>		
	</div>
	<?php endif; ?>

	
	<?php echo $this->loadTemplate('articles'); ?>
	

	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="row-fluid container-items-more-cat-children">
		<div class="cat-children">
			<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
			<h3>
				<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
			</h3>
			<?php endif; ?>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	</div>
	<?php endif; ?>

	
<!-- </div> -->

<?php /* resolve problema de rodapé escondendo conteúdo (https://github.com/joomlagovbr/idg2019/issues/13) */?>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
