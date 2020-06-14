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
?>
<div class="row">
	<?php if(count($items)): ?>
		<?php //echo '<pre>';  var_dump( $items);?>
		<?php foreach ($items as $key=>$item):	?>
			<?php if($params->get('itemVideo')): ?>
		      	<div class="<?php echo $params->get('header_class'); ?>">
		  			<a href="<?php echo $item->link; ?>">
			  			<div class="videosItem">
			  				<?php if(JFile::exists('media/k2/items'.DS.'cache'.DS.md5("Image".$item->id).'_M.jpg')): ?>
								<img src="<?php echo 'media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg'; ?>">
							<?php else: ?>
								<img src="templates/machado/img/player.png">
							<?php endif; ?>
			  			</div>
		  			</a>
		  			
		  			<div class="descricao">
		  				<a href="<?php echo $item->link; ?>">
		  					<div class="titulo"><?php echo $item->title;  ?></div>
		  				</a>	
		  				<strong><?php echo $item->video_caption ; ?></strong>
		  				<?php 
							$AliasExtraFields = array();
							foreach ($item->extra_fields as $key=>$extraField):
							  $AliasExtraFields[ $extraField->alias ] = $extraField;
							endforeach;
						?>
						<?php if($params->get('itemExtraFields') && count($item->extra_fields)): ?>
							<div class="detalhe duracao">
				              <span class="labelDescricao">Duração:</span>
				              <?php if( !isset($AliasExtraFields['duracao']) ):  ?>
				                <span>Não Informado</span>
				              <?php else: ?>  
				                <span class="tempo"><?php echo $AliasExtraFields['duracao']->value; ?></span>
				              <?php endif; ?>
				            </div>
						<?php endif; ?>	
	  				</div>
		  		</div>
		    <?php endif; ?>
		<?php endforeach; ?>
		<div class="row">
		<?php if($params->get('itemCustomLink')): ?>
			<div class="button-ver-mais">
				<a href="<?php echo $params->get('itemCustomLinkURL'); ?>" title="<?php echo K2HelperUtilities::cleanHtml($itemCustomLinkTitle); ?>"><?php echo $itemCustomLinkTitle; ?></a>
			</div>
		<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
