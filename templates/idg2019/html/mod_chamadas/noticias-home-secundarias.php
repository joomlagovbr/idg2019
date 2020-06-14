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

<section class="mosaico">
	<div class="container">
		<div class="row">
			<?php foreach ($lista_chamadas as $key => $lista): ?>
			<?php //foreach ($lista_chamadas as $lista): ?>
				<?php
					//Define link do artigo
					$link = JRoute::_(ContentHelperRoute::getArticleRoute($lista->id, $lista->catid));			
				?>
				<div class="col-md-4">
					<div class="item-mosaico mosaico-secundario">
						<div class="chamada-mosaico">
							<?php if ($params->get('chapeu') && ($lista->chapeu)): ?>
								<span class="chapeu-mosaico"><?php echo $lista->chapeu ?></span>
							<?php endif; ?>
							<?php if ($params->get('exibir_title')): ?>			
								<a href="<?php echo $link ?>" <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
									<?php echo $lista->title ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
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