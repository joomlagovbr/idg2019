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
