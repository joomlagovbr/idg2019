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

// Define default image size (do not change)
K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);

?>
<div class="col-md-3">
	<div class="videoItem">
		<a href="<?php echo $this->item->link; ?>">
			<?php if(empty($this->item->image)):?>
				<img src="templates/educa/img/player.png">
			<?php else: ?>
				<img src="<?php echo $this->item->image;?>">
			<?php endif; ?>
		</a>

		<div class="detalhes">
				<div class="titulo">
					<a href="<?php echo $this->item->link; ?>">
						<?php echo ($this->item->title); ?>
					</a>
				</div>
			<?php 
					$AliasExtraFields = array();
					foreach ($this->item->extra_fields as $key=>$extraField):
					  $AliasExtraFields[ $extraField->alias ] = $extraField;
					endforeach;
					?>
					<div class="duracao">
					<?php if($this->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>									
						
			              <span>Duração:</span>
			              <?php if( !isset($AliasExtraFields['duracao']) ):  ?>
			                Não Informado
			              <?php else: ?>  
			                <?php echo $AliasExtraFields['duracao']->value; ?>
			              <?php endif; ?>
	     			</div>
	     			</div>
	      	<?php endif; ?>	
			
	</div>

</div>

 