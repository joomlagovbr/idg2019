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
$input = JFactory::getApplication()->input;
$app     = JFactory::getApplication();
$view    = $app->input->getCmd('view', '');

$AliasExtraFields = array();
foreach ($this->item->extra_fields as $key=>$extraField):
  $AliasExtraFields[ $extraField->alias ] = $extraField;
endforeach;

?>
<div class="videotecaInterna">
  <div class="container">
    <div class="row detalheVideo">
      <?php if($this->item->params->get('itemTitle')): ?>
        <!-- Item title -->
        <h2><?php echo $this->item->title; ?></h2>
        <div class="underlineTitulo"></div>
      <?php endif; ?> 
            
      <div class="containerVideo">
                
        <div class="contentVideo">  
          <div class="headerVideo">
            <div class="wrapVideo"><?php echo $this->item->video; ?></div>
          </div>
          <?php if($this->item->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
            
            <div class="duracao">
              <span>Duração:</span>
              <?php if( !isset($AliasExtraFields['duracao']) ):  ?>
                <span>Não Informado</span>
              <?php else: ?>  
                <span><?php echo $AliasExtraFields['duracao']->value; ?></span>
              <?php endif; ?>
            </div>
            
          <?php endif; ?>
        </div>       
      </div>
    </div> <!-- CONTEUDO -->
  </div>
</div>
