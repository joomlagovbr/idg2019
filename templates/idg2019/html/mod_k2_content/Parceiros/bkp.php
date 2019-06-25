.resultadoBuscaParceiros .col-md-2,
.resultadoBuscaParceiros .col-md-4 {
	height: 300px;
}

.resultadoBuscaParceiros .col-md-2 { background: red  }
.resultadoBuscaParceiros .col-md-4 { background: green  }

<div class="col-md-6 itemparceiro" style="">
	<!-- <div class="col-md-4" style="padding: 0;"> -->
		<div class="parceirosItemlistagem" style="width:250px; height: 87px; background: red; float: left;">
		
		<a href="<?php echo $this->item->link; ?>">
			<?php preg_match_all("/<img.*\/>/",$this->item->text,$imagens); ?>
  			<?php if(!empty($imagens)):
  				foreach ($imagens as $key => $imagen) :
					$largura = "/width=\"[0-9]*\"/";
					$imagen[0] = preg_replace($largura, "", $imagen[0]);
					//$altura = "/height=\"[0-9]*\"/";
					//$imagen[0] = preg_replace($altura, "", $imagen[0]);
					echo ($imagen[0]);
				endforeach;
				?>
			<?php else: ?>
				<img src="templates/educa/img/parceiro.png">
			<?php endif; ?>
		</a>
		</div>
	<!-- </div> -->
	<!-- <div class="col-md-8" style="padding: 0;"> -->
		<div class="descricaoParceiro" style="background: green; float: left;">
			<p><a href="<?php echo $this->item->link; ?>"><?php echo $this->item->title; ?></a></p>
		</div>
	<!-- </div> -->

</div>

<!-- Novo codigo --> 
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

K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);

?>


<div class="col-md-2">
	
	<a href="<?php echo $this->item->link; ?>">
		<?php preg_match_all("/<img.*\/>/",$this->item->text,$imagens); ?>
			<?php if(!empty($imagens)):
				foreach ($imagens as $key => $imagen) :
				$largura = "/width=\"[0-9]*\"/";
				$imagen[0] = preg_replace($largura, "", $imagen[0]);
				//$altura = "/height=\"[0-9]*\"/";
				//$imagen[0] = preg_replace($altura, "", $imagen[0]);
				echo ($imagen[0]);
			endforeach;
			?>
		<?php else: ?>
			<img src="templates/educa/img/parceiro.png">
		<?php endif; ?>
	</a>

</div>
<div class="col-md-4">
	
	<a href="<?php echo $this->item->link; ?>"><?php echo $this->item->title; ?></a>
	
</div>

