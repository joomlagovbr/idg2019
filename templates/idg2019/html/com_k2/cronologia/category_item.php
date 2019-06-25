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
<?php 
			$AliasExtraFields = array();
			foreach ($this->item->extra_fields as $key=>$extraField):
			  $AliasExtraFields[ $extraField->alias ] = $extraField;
			endforeach;
		?>
<?php //var_dump($this->item->extra_fields) ?>
		<div class="col-md-3">
			<div class="item" >	
				<div class="link">
					<a href="<?php echo $this->item->link; ?>"> <?php echo $this->item->title; ?></a>
				</div>
					
				<?php echo $AliasExtraFields['imagem']->value; ?>
				
			</div>
		</div>

