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
$linkObra = strip_tags($this->item->extra_fields[0]->value);

?>
<?php 
			$AliasExtraFields = array();
			foreach ($this->item->extra_fields as $key=>$extraField):
			  $AliasExtraFields[ $extraField->alias ] = $extraField;
			endforeach;
		?>
<div class="col-md-3">



	<div class="item" >
				<?php if($AliasExtraFields['Link']->value): ?>
				<a href="<?php echo $linkObra;?>" target="blank" title="<?php echo $this->item->title; ?>">
					<?php echo $this->item->title; ?>
				</a>

			<?php endif; ?>

				<?php if ($this->item->attachments): ?>
				<div class="titulo">
					<?php echo $this->item->title; ?><br>
					(<?php echo $AliasExtraFields['ano']->value; ?>)
				</div>
				<div class="download">
            		<a href="<?php echo $this->item->attachments[0]->link?>" title="Download"><img class="efeito" src="templates/machado/img/iconPdf.png"></a>
    			</div>
    		<?php endif; ?>
				
				
			
		<?php foreach ($this->item->tags as $key=>$tags): ?>
			<?php if(!empty($tags)): ?>
				<div class="imgCategoria"><img src="templates/educa/img/categorias/<?php echo JFilterOutput::stringURLSafe($tags->name); ?>.png" alt="<?php echo $tags->name; ?>"></div>
			<?php endif; ?>
		<?php endforeach; ?>

		

		



		<?php if($this->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
            
                    
            <div class="descricao">
            	<?php echo $AliasExtraFields['descricao']->value; ?>
            </div>

           

	        
	
    
    	

           
      	<?php endif; ?>
		
	</div>
	
	
		
	
</div>

