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
            default:
                $cor_capa = "verde";
                ;

        }
?>
<div class="row">
  <div class="col-md-3">
    <?php foreach ($months as $month): ?>
    <div class="s <?php echo $cor_capa; ?>">
      <a href="<?php echo $month->link; ?>">
        <?php echo $month->name.' '.$month->y; ?>
        <?php if ($params->get('archiveItemsCounter')) echo '('.$month->numOfItems.')'; ?>
      </a>
      <div class="fundo-livro"></div> 
    </div>
    <?php endforeach; ?>
  </ul>
</div>
