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
		<?php foreach ($items as $key=>$item):	?>
			<?php
				$cor_capa = '';

				switch ($item->categoryid) {
				    case 4: //BULLYING E VIOLÊNCIAS
				        $cor_capa = "verde";
				        break;
				    case 5: //GÊNERO E DIVERSIDADE SEXUAL
				        $cor_capa = "roxo";
				        break;
				    case 6: //ÉTICA E CIDADANIA
				        $cor_capa = "azul";
				        break;
			        case 7: //EDUCAÇÃO PARA AS RELAÇÕES ÉTNICO-RACIAIS
				        $cor_capa = "mostarda";
				        break;
			        case 8: //EDUCAÇÃO E O SISTEMA SOCIOEDUCATIVO
				        $cor_capa = "laranja";
				        break;
				    case 9: //REDE DE PROTEÇÃO INTEGRAL E SISTEMA DE GARANTIA DE DIREITOS
				        $cor_capa = "amarelo";
				        break;
				    case 10: //LEGISLAÇÃO
				        $cor_capa = "verde-claro";
				        break;
			        case 11: //CARTILHAS
				        $cor_capa = "rosa";
				        break;
				     case 20: //EDUCACAO ESPECIAL
			                $cor_capa = "vermelho";
			            break;			               
		               
	       			}				
			?>

		
			<?php if($params->get('itemImage') || $params->get('itemIntroText')): ?>
		      	<div class="<?php echo $params->get('header_class'); ?>" >		  			
		  			<div class="obra <?php echo $cor_capa; ?>">
	      				<a href="<?php echo $item->link; ?>" title="<?php echo JText::_('K2_CONTINUE_READING'); ?> &quot;<?php echo K2HelperUtilities::cleanHtml($item->title); ?>&quot;">
					      	<?php if(isset($item->image)): ?>
					      		<img src="<?php echo $item->image; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($item->title); ?>" />
					      	<?php else: ?>
					      		<span class="catTitle"><?php echo K2HelperUtilities::cleanHtml($item->title); ?></span>
					      	<?php endif; ?>
				      	</a>
				      	<div class="fundos-livro"></div> 
		  			</div>
		  			
	  				<!-- <div class="descricao">
						<?php 
							$AliasExtraFields = array();
							foreach ($item->extra_fields as $key=>$extraField):
							  $AliasExtraFields[ $extraField->alias ] = $extraField;
							endforeach;
						?>
						<?php if($params->get('itemExtraFields') && count($item->extra_fields)): ?>
				            <div class="detalhe categoria">
				              <span class="labelDescricao">Tema:</span>
				              <?php if( !isset($AliasExtraFields['tema']) ):  ?>
				                <span>Não Informado</span>
				              <?php else: ?>  
				                <?php if(strlen($AliasExtraFields['tema']->value) > 28):?>
				                	<abrr title="<?php echo $AliasExtraFields['tema']->value; ?>"><span><?php echo mb_substr($AliasExtraFields['tema']->value, 0, 25)."..."; ?></span></abrr>
			                	<?php else: ?>
		                			<span><?php echo $AliasExtraFields['tema']->value; ?></span>
				              	<?php endif; ?>  
				              <?php endif; ?>  
				            </div>
				            
				            <div class="detalhe autor">
				              <?php if(!isset($AliasExtraFields['autor']) && !isset($AliasExtraFields['organizador'])):  ?>
				                <span class="labelDescricao">Autor(a):</span>
				                <span>Não Informado</span>
				              <?php elseif(isset($AliasExtraFields['autor'])): ?>  
				                <span class="labelDescricao">Autor(a):</span>
				                <?php if(strlen($AliasExtraFields['autor']->value) > 18):?>
					                <abrr title="<?php echo $AliasExtraFields['autor']->value; ?>"><span><?php echo mb_substr($AliasExtraFields['autor']->value, 0, 17)."..."; ?></span></abrr>			                	
			                	<?php else: ?>
		                			<span><?php echo $AliasExtraFields['autor']->value; ?></span>
				              	<?php endif; ?>
				              <?php elseif(isset($AliasExtraFields['organizador'])): ?>  
				                <span class="labelDescricao">Organizador(a):</span>
				                <?php if(strlen($AliasExtraFields['organizador']->value) > 15):?>
					                <abrr title="<?php echo $AliasExtraFields['organizador']->value; ?>"><span><?php echo mb_substr($AliasExtraFields['organizador']->value, 0, 14)."..."; ?></span></abrr>	
			                	<?php else: ?>
		                			<span><?php echo $AliasExtraFields['organizador']->value; ?></span>
				              	<?php endif; ?>
				              <?php endif; ?>
				            </div>
				            
				            <div class="detalhe ano">
				              <span class="labelDescricao">Ano:</span>
				              <?php if( !isset($AliasExtraFields['ano']) ):  ?>
				                <span>Não Informado</span>
				              <?php else: ?>  
				                <span><?php echo $AliasExtraFields['ano']->value; ?></span>
				              <?php endif; ?>
				            </div>
			          	<?php endif; ?>
	  				</div>-->
		  		</div> 

		    <?php endif; ?>
		<?php endforeach; ?>

		<?php if($params->get('itemCustomLink')): ?> 
			<div class="buttomBiblioteca">
				<a href="<?php echo $params->get('itemCustomLinkURL'); ?>" title="<?php echo K2HelperUtilities::cleanHtml($itemCustomLinkTitle); ?>"><?php echo $itemCustomLinkTitle; ?></a>
			</div>
		<?php endif; ?>
		
	<?php endif; ?>
</div>
