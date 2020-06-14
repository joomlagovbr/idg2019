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
		<?php if ($module->title) : ?>
			<h2><?php echo $module->title; ?></h2>
		<?php endif; ?>
		<div class="row">
			<div class="col-md-4">
				<div class="item-destaques <?php echo $item->clickurl ? '' : 'tipo2'; ?>">


			<?php foreach ($lista_chamadas as $key => $lista): ?>
			<?php //foreach ($lista_chamadas as $lista): ?>
				<?php
					//Define link do artigo
					$link = JRoute::_(ContentHelperRoute::getArticleRoute($lista->id, $lista->catid));	
					echo $lista->extrafields->link_do_item;
					echo "<br>";
					echo $lista->extrafields->chapeu;
					echo "<br>";
					echo $lista->extrafields->tipo_de_link_do_item;
					echo "<br>";
					echo $lista->extrafields->imagem_de_fundo;
					echo "<br>";
					echo $lista->extrafields->cor_de_fundo;
					echo "<br>";
					echo $lista->extrafields->tamanho_do_titulo;
					echo "<br>";
					echo $lista->extrafields->texto_do_botao_com_link;
					echo "<br>";
				?>
				</div>
			</div>
			<div class="col-md-4 text-center" data-panel="">
			    <div class="tile tile-default">
			    	<?php if ($params->get('exibir_imagem') && !empty($lista->image_url)): ?>
	                    <div class="image-container">
	                        <a href="<?php echo $lista->link ?>">
	                            <img src="<?php echo $lista->image_url ?>" alt="<?php echo $lista->image_alt ?>" />
	                        </a>
	                    </div>
	                <?php endif; ?>		
			    	<br>
			        <?php if ($params->get('chapeu') && ($lista->extrafields->chapeu)): ?>
						<span class="chapeu-box"><?php echo $lista->extrafields->chapeu ?></span>
					<?php endif; ?>
					<br>
					<?php if ($params->get('exibir_title')): ?>			
						<?php echo $lista->title ?>
					<?php endif; ?>
			       
			    </div>


				<div class="botoes-centro">
					<a href="<?php echo $lista->extrafields->link_do_item; ?>" class="btn-padrao"><?php echo $lista->extrafields->texto_do_botao_com_link; ?></a>	
				</div>
			        
			</div>
	    <?php endforeach; ?>

	
		</div>
	</div>
</section>
<!-- NOTICIAS END -->