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
<?php

$AliasExtraFields = array();
foreach ($this->item->extra_fields as $key=>$extraField):
  $AliasExtraFields[ $extraField->alias ] = $extraField;
endforeach;
?>

<div class="interna">
  <div class="container">
    
      <?php if($this->item->params->get('itemTitle')): ?>
        <!-- Item title -->
        <h2><?php echo $this->item->title; ?> - <span class="anoObra"><?php echo $AliasExtraFields['ano']->value; ?></span></h2> 
        <div class="underlineTitulo"></div>
      <?php endif; ?> 
            
      <div class="containerObra">
        <div class="contentLivro">  
          
          <?php if($this->item->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
              <div class="detalhe categoria">
              
              <?php if(!isset($this->item->category->name)):  ?>
                <span>Não Informado</span>
              <?php else: ?>  
                <span><?php echo $this->item->category->name; ?></span>
              <?php endif; ?>  
            </div>
              
            <div class="detalhe download">
                <?php if(!isset($this->item->attachments[0]->filename)):  ?>
                <span class="semDownload">Sem arquivo para download</span>
              <?php else: ?>  
                <div class="downloadObra"><span><a href="<?php echo $this->item->attachments[0]->link; ?>">Clique para baixar o arquivo</a></span><span></div>
              <?php endif; ?>
            </div>
            
            
            
          <?php endif; ?>
        </div>       
      </div>
    
  </div>
</div>
