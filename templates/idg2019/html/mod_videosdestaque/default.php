<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_agendadirigentes
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
?>
<section class="mosaico">
	<div class="container">
		<div class="row">
			<?php
			$span_unit = 12 / count($items->name);
			for ($i=0, $limit = count($items->name); $i < $limit; $i++):
				$class = 'module span' . $span_unit;
			?>
				<div class="col-md-4">
					<div class="item-mosaico">
						<?php //if ($params->get('exibir_imagem')): ?>
							<?php //if (!empty($lista->images->image_intro)): ?>
								<!-- <img class="foto-mosaico" src="<?php //echo $lista->images->image_intro ?>" /> -->
								<?php ModVideosDestaqueHelper::showPlayer( $items->url[$i], count($items->url) ); ?>
							<?php //endif; ?>
						<?php //endif; ?>
						<div class="chamada-mosaico">
							<?php //if ($params->get('chapeu') && ($lista->chapeu)): ?>
								<span class="chapeu-mosaico"><?php echo $items->name[$i]; ?></span>
							<?php //endif; ?>
							<?php //if ($params->get('exibir_title')): ?>			
								<a href="<?php echo $link ?>" <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
									<?php echo $items->description[$i]; ?>
								</a>
							<?php //endif; ?>
						</div>
					</div>
				</div>

				<!-- <div class="<?php echo $class ?>">
					<div class="video">
						<?php ModVideosDestaqueHelper::showPlayer( $items->url[$i], count($items->url) ); ?>
					</div>
					<h2><strong><?php echo $items->name[$i]; ?></strong></h2>
					<p class="description"><?php echo $items->description[$i]; ?></p>
				</div> -->
			<?php 
			endfor;
			?>
	
		    <?php if( !empty($text_link_footer) && !empty($url_link_footer) ): ?>
		    <!-- <div class="outstanding-footer">
		        <a href="<?php echo $url_link_footer ?>" class="outstanding-link">
		            <span class="text"><?php echo $text_link_footer; ?></span>
		            <span class="icon-box">                                          
		              <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
		            </span>
		        </a>
		    </div> -->


		    <div class="botoes-centro">
				<a href="<?php echo $url_link_footer ?>" class="btn-padrao"><?php echo $text_link_footer; ?></a>	
			</div>  
			<?php endif; ?>
		</div>
	</div>
</section>