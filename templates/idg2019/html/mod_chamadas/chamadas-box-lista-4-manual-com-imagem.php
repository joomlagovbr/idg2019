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

<section class="chamada-box">
	<div class="container">
		<div class="row">


			<?php foreach ($lista_chamadas as $key => $lista): ?>
			<?php //foreach ($lista_chamadas as $lista): ?>
				<?php
					//Define link do artigo
					$link = JRoute::_(ContentHelperRoute::getArticleRoute($lista->id, $lista->catid));			
				?>
			
			<div class="<?php echo $params->get('header_class') ?> text-center" data-panel="">
			    <div class="tile tile-default">
			    	<?php if ($params->get('exibir_imagem') && !empty($lista->image_url)): ?>
	                    <div class="image-container">
	                        <a href="<?php echo $lista->link ?>" title="<?php echo $lista->title ?>">
	                            <img src="<?php echo $lista->image_url ?>" alt="<?php echo $lista->image_alt ?>" />
	                        </a>
	                    </div>
	                <?php endif; ?>		
			    	<br>
			        <?php if ($params->get('chapeu') && ($lista->chapeu)): ?>
						<span class="tag-chapeu"><?php echo $lista->chapeu ?></span>
					<?php endif; ?>
					<br>
					<?php if ($params->get('exibir_title')): ?>			
						<a href="<?php echo $lista->link; ?>" title="<?php echo $lista->title ?>"><?php echo $lista->title ?></a>	
					<?php endif; ?>
			       
			    </div>


				<!-- <div class="botoes-centro">
					<a href="<?php echo $lista->link; ?>" class="btn-padrao">Acesse</a>	
				</div> -->
			        
			</div>
	    <?php endforeach; ?>
	    
		<?php if (! empty($link_saiba_mais) ): ?>
		<div class="botoes-centro">
			<a href="<?php echo $link_saiba_mais; ?>" class="btn-padrao">
				<?php if ($params->get('texto_saiba_mais')): ?>
					<?php echo $params->get('texto_saiba_mais')?>
				<?php else: ?>
					Mais Notícias
				<?php endif;?>
			</a>	
		</div>
		<?php endif; ?>

	
		</div>
	</div>
</section>
<!-- NOTICIAS END -->