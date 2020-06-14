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
require_once __DIR__.'/_helper.php';
$input = JFactory::getApplication()->input;
$app     = JFactory::getApplication();
$view    = $app->input->getCmd('view', '');


/*echo '<pre>';
var_dump($this->params);*/

?>
<div class="autor-obra-galeria">
<div class="interna">
	<div class="container">
		
			<?php //if($this->params->get('show_page_title')): ?>
				<!-- Page title -->
				<h2><?php echo $this->escape($this->params->get('page_title')); ?></h2>
				<div class="underlineTitulo"></div>
			<?php //endif; ?>
			
			<div class="item-page">
				<?php if($this->params->get('catDescription')): ?>
					<!-- Category description -->
					<div><?php echo $this->category->description; ?></div>
				<?php endif; ?>

				<div class="formularioPesquisa">
					<form method="GET" action="<?php echo JURI::root(); ?>index.php" target="_self">
						<input type="hidden" name="option" value="com_k2">
						<input type="hidden" name="view" value="itemlist">
						<input type="hidden" name="layout" value="category">
						<input type="hidden" name="task" value="category">
						<label for="cats" class="categorias hidden">Categorias</label>
						<select name="id" class="inputbox" onchange="this.form.submit()">
							<option value="32" selected>Categorias</option>
							<?php 
								//Adicionar no "value" do "option" e no parametro da função o id da categoria K2 que deseja filtrar
								$categorias = TemplateK2CategoryHelper::getCategories(32); 
							?>
							<?php foreach($categorias as $key=>$itemCat): ?>
								<?php if($input->getInt('id') ==  $itemCat[0]): ?>
									<option value="<?php echo $itemCat[0]; ?>" selected><?php echo $itemCat[1]; ?></option>
								<?php else: ?>
									<option value="<?php echo $itemCat[0]; ?>"><?php echo $itemCat[1]; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>

						<select name="order" class="inputbox" onchange="this.form.submit()">
						<?php 
							//Categorias que tem extrafield ano.
							if($input->getInt('id') == 34){ ?>
							<?php if($input->get("order") == 'year' || $input->get("order") == ''): ?>
								<option value="year" selected="">Ordenar por ano</option>
							<?php else: ?>
								<option value="year">Ordenar por ano</option>
							<?php endif; ?>

							<?php if($input->get("order") == 'rdate'): ?>
								<option value="rdate" selected="">Ordenar pelo mais recente</option>
							<?php else: ?>
								<option value="rdate">Ordenar pelo mais recente</option>
							<?php endif; ?>
						<?php }else{ ?>
							<?php if($input->get("order") == 'rdate' || $input->get("order") == ''): ?>
								<option value="rdate" selected="">Ordenar pelo mais recente</option>
							<?php else: ?>
								<option value="rdate">Ordenar pelo mais recente</option>
							<?php endif; ?>
						<?php } ?>

							<?php if($input->get("order") == 'alpha'): ?>
								<option value="alpha" selected="">Ordenar alfabeticamente por título</option>
							<?php else: ?>
								<option value="alpha">Ordenar alfabeticamente por título</option>
							<?php endif; ?>
						</select>	

						<label for="pesquisa" class="pesquisa hidden">Pesquisa</label>
						<input size="40" placeholder="Digite a sua pesquisa" value="<?php echo $input->getVar("searchword"); ?>" id="searchword" name="searchword" type="text"><button type="submit"><i class="glyphicon glyphicon-search"></i></button> 
						<input type="hidden" name="Itemid" value="<?php echo $input->getInt("Itemid"); ?>">						
					</form>
              	</div>

				<div class="resultadoBusca">
					<div class="row">
						<?php if(empty($this->leading)): ?>

							<div class="container alertaBusca">
								Nenhum livro encontrado.
							</div>
						<?php else: ?>
							<!-- ITENS -->	
							<?php foreach($this->leading as $key=>$item): ?>
								<?php
									// Load category_item.php by default
									$this->item=$item;
									echo $this->loadTemplate('item');
								?>
							<?php endforeach; ?>
							<!-- FINAL INTENS -->
						<?php endif; ?>	
					</div>
				</div>									
			</div>
			<!-- Pagination -->
			<?php if($this->pagination->getPagesLinks()): ?>
			<div class="pagination">
				<?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
				<?php //if($this->params->get('catPaginationResults')) echo $this->pagination->getPagesCounter(); ?>
			</div>
			<?php endif; ?>
		
	</div>
</div>
</div>