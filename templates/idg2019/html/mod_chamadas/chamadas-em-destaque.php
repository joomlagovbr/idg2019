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

<section class="destaques">
	<div class="container">
		
		<div class="row">

			<?php foreach ($lista_chamadas as $key => $lista): ?>
				<?php
					//Define link do artigo
					$link = JRoute::_(ContentHelperRoute::getArticleRoute($lista->id, $lista->catid));	
					$class_cor_de_fundo = substr($lista->extrafields->cor_de_fundo,0,1);
				?>
				<div class="col-md-4">
			        <div class="item-destaques <?php echo $lista->extrafields->cor_de_fundo == '' ? '' : 'tipo2'; ?>">
			          	<?php if ($params->get('exibir_imagem') && !empty($lista->image_url)): ?>
	                        <img src="<?php echo $lista->image_url ?>" class="foto-destaques" alt="<?php echo $lista->image_alt ?>" />
		                <?php endif; ?>

			          	<div class="chamada-destaques <?php echo $class_cor_de_fundo != '#' ? $lista->extrafields->cor_de_fundo : ''; ?>" <?php echo $class_cor_de_fundo != '#' ? '' : 'style="background:'.$lista->extrafields->cor_de_fundo.' !important;"'; ?>>

			          		<?php if ($params->get('exibir_title')): ?>

			          		<?php //echo '<pre>'; var_dump($lista->extrafields); echo '</pre>';?>	

			          		<?php switch ($lista->extrafields->tipo_de_link_do_item) {
			          				case 'botao': ?>
			          					<span class="chapeu-destaques"><?php echo $lista->titulo_alternativo == '' ? $lista->title : $lista->titulo_alternativo ?></span>
				            			<a href="<?php echo $lista->extrafields->link_do_item; ?>" class="titulo-destaques"><?php echo $lista->extrafields->texto_do_botao_com_link; ?></a>
				            			<?php
			          					break;
			          				case 'geral': ?>
			          					<a href="<?php echo $lista->extrafields->link_do_item; ?>" class="titulo-destaques"><?php echo $lista->title ?></a>
				            			<?php
			          					break;
		          					case 'titulo': ?>
			          					<span class="chapeu-destaques"><?php echo $lista->extrafields->chapeu_box;?></span>
				            			<a href="<?php echo $lista->extrafields->link_do_item; ?>" class="titulo-destaques"><?php echo $lista->extrafields->titulo_alternativo == '' ? $lista->title : $lista->extrafields->titulo_alternativo ?></a>
				            			<?php
			          					break;
			          			}

			          			?>
							<?php endif; ?>
			          	</div>
			        </div>
		      	</div>
	    <?php endforeach; ?>
		</div>
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
</section>
<!-- NOTICIAS END -->