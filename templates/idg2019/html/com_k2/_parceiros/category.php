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
require_once __DIR__.'/_helper.php';
$input = JFactory::getApplication()->input;
?>
<div class="interna parceiros">
	<div class="container">
		<div class="row">
			<?php //if($this->params->get('show_page_title')): ?>
				<!-- Page title -->
				<h2><?php echo $this->escape($this->params->get('page_title')); ?></h2>
			<?php //endif; ?>
			
			<div class="item-page">
				<?php if($this->params->get('catDescription')): ?>
					<!-- Category description -->
					<div><?php echo $this->category->description; ?></div>
				<?php endif; ?>
				<div class="resultadoBuscaParceiros">
					<div class="row">
					<?php if(empty($this->leading)): ?>	
							<div class="row">
								<h3>Nenhum video encontrado</h3>
							</div>
						<?php else: ?>
							<!-- ITENS -->	
							<?php foreach($this->leading as $key=>$item): ?>
								<?php
									// Load category_item.php by default
									$this->item=$item;
									echo $this->loadTemplate('item');
								?>
							<?php endforeach; ?>
							<!-- FINAL INTENS -->
						<?php endif; ?>	
					</div>
				</div>									
			</div>
			<!-- Pagination -->
			<?php if($this->pagination->getPagesLinks()): ?>
			<div class="pagination">
				<?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
				<?php //if($this->params->get('catPaginationResults')) echo $this->pagination->getPagesCounter(); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
