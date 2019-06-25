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
		<?php //echo '<pre>';  var_dump( $items);?>
		<?php foreach ($items as $key=>$item):	?>
			<?php if($params->get('itemImage')): ?>
		      	<div class="<?php echo $params->get('header_class'); ?>">
		  			<a href="<?php echo $item->link; ?>">
			  			<div class="parceirosItem">
		      				<?php preg_match_all("/<img.*\/>/",$item->text,$imagens); ?>
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
			  			</div>
		  			</a>
		  			<div class="descricao"><?php echo $item->title ; ?></div>
		  		</div>
		    <?php endif; ?>
		<?php endforeach; ?>
		<div class="row">
		<?php if($params->get('itemCustomLink')): ?>
			<div class="buttomParceiros">
				<a href="<?php echo $params->get('itemCustomLinkURL'); ?>" title="<?php echo K2HelperUtilities::cleanHtml($itemCustomLinkTitle); ?>"><?php echo $itemCustomLinkTitle; ?></a>
			</div>
		<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
