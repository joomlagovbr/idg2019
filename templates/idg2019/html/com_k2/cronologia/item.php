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

$AliasExtraFields = array();
foreach ($this->item->extra_fields as $key=>$extraField):
  $AliasExtraFields[ $extraField->alias ] = $extraField;
endforeach;

?>
<div class="cronologiaItem">
  <div class="container">
    <div class="row">
      <?php if($this->item->params->get('itemTitle')): ?>
        <!-- Item title -->
       
      <?php endif; ?> 
         
      <div class="tituloCronologia">Cronologia</div> 
      <div class="underlineTitulo"></div>     
      
         <?php if($this->item->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
          
      <div class="col-md-12">
        <div class="col-md-4">
          <div><?php echo $AliasExtraFields['imagem']->value; ?></div>
        </div>
        <div class="col-md-8">  
          <div class="ano"><?php echo $this->item->title; ?></div>
          <div class="texto"><?php echo $AliasExtraFields['texto']->value; ?></div>
        </div>
      </div>
            

                
                
          
           
          <?php endif; ?>
             
    
    </div> <!-- CONTEUDO -->
  </div>
</div>
