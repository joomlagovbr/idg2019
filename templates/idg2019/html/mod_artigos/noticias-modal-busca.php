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

<div class="col-md-<?php echo $params->get('moduleclass_sfx'); ?>">
	<span class="titulo-busca <?php echo $params->get('class_sfx'); ?>"><?php echo $module->title; ?></span>

	<ul>

		<?php foreach ($lista_chamadas as $key => $lista): ?>
		<?php
			//Define link do artigo
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($lista->id, $lista->catid));			
		?>
			<li>
				<a href="<?php echo $link ?>"<?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?> title="<?php echo $lista->title ?>">
					<?php echo $lista->title ?>
				</a>
			</li>
		<?php endforeach; ?>
		
	</ul>
</div>

<!-- NOTICIAS END -->