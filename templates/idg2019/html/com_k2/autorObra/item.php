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
$linkUrl = strip_tags($this->item->extra_fields[0]->value);

?>



<?php

$AliasExtraFields = array();
foreach ($this->item->extra_fields as $key=>$extraField):
  $AliasExtraFields[ $extraField->alias ] = $extraField;
endforeach;
?>



<div class="interna autorObraListagem">
  <div class="container">
    <div>
      <?php //var_dump($AliasExtraFields['Link']->value) ?>
      <?php if($this->item->params->get('itemTitle')): ?>
        <!-- Item title -->
        <h2><?php echo $this->item->title; ?>: <span><?php echo $AliasExtraFields['ano']->value; ?></span> </h2>
        <div class="underlineTitulo"></div>

      <?php endif; ?>         
          

          <?php if($this->item->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
            <div class="detalhe categoria">
              
              <?php if(!isset($this->item->category->name)):  ?>
                <span>Não Informado</span>
              <?php else: ?>  
                <span><?php echo $this->item->category->name; ?></span>
              <?php endif; ?>  
            </div>
            
            <?php if ($this->item->attachments[0]->link):?>
            <div class="detalhe download">           
              <div class="downloadObra"><span><a href="<?php echo $this->item->attachments[0]->link; ?>" target="_blank">Clique para baixar o arquivo</a></span><span></span></div>
            </div>

          <?php else: ?>

            <div class="detalhe download">           
              <div class="downloadObra"><span><a href="<?php echo $linkUrl ; ?>" target="_blank">Clique para acessar o arquivo</a></span><span></span></div>
            </div>

          <?php endif; ?>



           
                  
              
           
            
          
          <?php endif; ?>
              
      
    </div> <!-- CONTEUDO -->
  </div>
</div>
