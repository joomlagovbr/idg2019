<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>

<section class="tipo-banners">

	<div class="listagem-chamadas-secundarias row">
	    <?php foreach ($lista_chamadas as $k => $lista): ?>
	    	<?php 
	    	$grid = 12;	
			$valor = (count($lista_chamadas));
			?>
	        <?php $p = $k + 1; ?>
	        <div class="col-md-<?php echo ceil($grid/$valor); ?> item-banner">
	            <?php if ($params->get('mostra_numeracao')) : ?>
	                <div class="span1"><?php echo $p; ?></div>
	                <div class="span11">
	            <?php endif; ?>
	                <?php if (@$lista->chapeu): ?>
	                    <p class="subtitle-container">
	                        <?php echo $lista->chapeu ?>
	                    </p>
	                <?php endif; ?>
	                <?php if ($params->get('exibir_imagem') && !empty($lista->image_url)): ?>
	                    <div class="image-container">
	                        <a href="<?php echo $lista->link ?>">
	                            <img src="<?php echo $lista->image_url ?>" width="200" height="130" class="img-rounded" alt="<?php echo $lista->image_alt ?>" />
	                        </a>
	                    </div>
	                <?php endif; ?>		
	                <div class="content-container">
	                    <h3><a href="<?php echo $lista->link ?>"><?php echo $lista->title ?></a></h3>
	                    <div class="description">
	                        <?php echo $lista->introtext; ?>
	                    </div>
	                </div>
	                <?php if ($params->get('mostra_numeracao')) : ?>
	                </div>
	            <?php endif; ?>
	        </div>
	    <?php endforeach; ?>
	</div>
</section>
<?php if (! empty($link_saiba_mais) ): ?>
	<div class="outstanding-footer">
		<a href="<?php echo $link_saiba_mais; ?>" class="outstanding-link">
			<?php if ($params->get('texto_saiba_mais')): ?>
				<span class="text"><?php echo $params->get('texto_saiba_mais')?></span>
			<?php else: ?>
				<span class="text">saiba mais</span>
			<?php endif;?>
			<span class="icon-box">                                          
		      <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
		    </span>
		</a>	
	</div>
<?php endif; ?>