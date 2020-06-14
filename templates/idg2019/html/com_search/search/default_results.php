<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
?>

<ul class="todas-noticias <?php echo $this->pageclass_sfx; ?>">
	<?php foreach($this->results as $result) : ?>
		<li>
			<span class="chapeu-noticia">
				
			</span>

			<?php if ($result->href) :?>
				<!-- <a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>> -->
				<a href="<?php echo preg_match('/AGENDADIRIGENTES/', strtoupper($result->href)) ? JRoute::_($result->href).'&Itemid=101' : JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>>
					<?php echo $result->title;?>
				</a>
			<?php else:?>
				<?php echo $result->title;?>
			<?php endif; ?>

			<div class="descricaoNoticia">
				<?php echo $result->text; ?>
			</div>
	  		<div class="keywords">
	  			<?php if(@$result->metakey != ''): ?>
		  			tags: 	
		  			<span><?php TemplateSearchHelper::displayMetakeyLinks( $result->metakey, '', $this->escape($this->origkeyword) ); ?> </span>
	  			<?php endif; ?>
				
	  		</div>
	  		<?php if ($this->params->get('show_date') && $result->created != '') : ?>
	  			<span class="data-noticia">
	  				<?php echo JText::sprintf($result->created); ?> - <?php echo $this->escape($result->section); ?>
  				</span>
  			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>