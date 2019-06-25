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
	<div class="item" >
		<div class="detalhes"> 
			<?php if(strlen($this->item->title) >= 76):?>
				<div class="titulo">
					<?php echo $this->item->title; ?>
				</div>
			<?php else: ?>
				<div class="titulo"> 
					<?php echo $this->item->title; ?>
				</div>
			<?php endif; ?>

			

			
		<?php foreach ($this->item->tags as $key=>$tags): ?>
			<?php if(!empty($tags)): ?>
				<div class="imgCategoria"><img src="templates/educa/img/categorias/<?php echo JFilterOutput::stringURLSafe($tags->name); ?>.png" alt="<?php echo $tags->name; ?>"></div>
			<?php endif; ?>
		<?php endforeach; ?>
			
	
		<?php 
			$AliasExtraFields = array();
			foreach ($this->item->extra_fields as $key=>$extraField):
			  $AliasExtraFields[ $extraField->alias ] = $extraField;
			endforeach;
		?>
		<?php if($this->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
                     
            
            <div class="detalhe ano">
             <?php echo $AliasExtraFields['pdf']->value; ?>
              <?php if( !isset($AliasExtraFields['ano']) ):  ?>
                Não Informado
              <?php else: ?>  
                <?php echo $AliasExtraFields['ano']->value; ?>
              <?php endif; ?>
            </div>

            

      	<?php endif; ?>
	
	</div>
	<div class="download">
            	<a href="<?php echo $this->item->attachments[0]->link?>" title="Download"><img class="efeito" src="templates/machado/img/iconPdf.png"></a>
    </div>
</div>


</div>
