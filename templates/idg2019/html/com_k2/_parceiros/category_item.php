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
<div class="col-md-6 itemparceiro" >
	<div class="col-md-4" style="padding: 0;">
		<div class="parceiroItem">
		<a href="<?php echo $this->item->link; ?>">
			<?php preg_match_all("/<img.*\/>/",$this->item->text,$imagens); ?>
  			<?php if(!empty($imagens)):
  				foreach ($imagens as $key => $imagen) :
					$largura = "/width=\"[0-9]*\"/";
					$imagen[0] = preg_replace($largura, "", $imagen[0]);
					echo ($imagen[0]);
					endforeach;
					?>
			<?php else: ?>
				<img src="templates/educa/img/parceiro.png">
			<?php endif; ?>	
		</a>
		</div>
	</div>
	<div class="col-md-8" style="padding: 0;">
		<div class="descricaoParceiro">
			<p><a href="<?php echo $this->item->link; ?>"><?php echo $this->item->title; ?></a></p>
		</div>
	</div>
</div>




<script>
	 jQuery(document).ready(function() {
        jQuery( ".resultadoBuscaParceiros .parceiroItem" ).first().find("IMG").attr( "style", "width:64px" );
    });
</script>