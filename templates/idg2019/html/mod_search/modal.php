<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Including fallback code for the placeholder attribute in the search field.
JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);

if ($width)
{
	$moduleclass_sfx .= ' ' . 'mod_search' . $module->id;
	$css = 'div.mod_search' . $module->id . ' input[type="search"]{ width:auto; }';
	JFactory::getDocument()->addStyleDeclaration($css);
	$width = ' size="' . $width . '"';
}
else
{
	$width = '';
}
?>
<div class="box-busca">
	<div class="container">
		<div class="fechar-modal">Fechar</div>
		<div class="quadro-busca">
			<div class="campo-busca">
				<form action="<?php echo JRoute::_('index.php');?>" method="post" class="form-inline">
					<div class="form-group">
								
					<?php
						$output = '<label for="mod-search-searchword" class="sr-only">' . $label . '</label> ';

						$output .= '<input name="searchword" id="mod-search-searchword" maxlength="' . $maxlength . '"  class="inputbox search-query form-control" type="search"'.$width.' placeholder="' . $text . '" size="100%"/>';
						//$button = '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>';

						if ($button) :
							if ($imagebutton) :
								$btn_output = ' <input type="image" alt="" class="button" src="" onclick="this.form.searchword.focus();"/>';
							else :
								$btn_output = ' <button onclick="this.form.searchword.focus();" type="submit" class="botao-busca" >' . $button_text . '</button>';
							endif;

							switch ($button_pos) :
								case 'top' :
									$output = $btn_output . '<br />' . $output;
									break;

								case 'bottom' :
									$output .= '<br />' . $btn_output;
									break;

								case 'right' :
									$output .= $btn_output;
									break;

								case 'left' :
								default :
									$output = $btn_output . $output;
									break;
							endswitch;

						endif;

						echo $output;
					?>
					<input type="hidden" name="task" value="search" />
					<input type="hidden" name="option" value="com_search" />
					<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
					</div>
				</form>
			</div>
			<div class="sugestoes-busca">
				<div class="row">

					<?php 

					// Monta posição virtual para escrever menu interno.
					$document = JFactory::getDocument();
					$renderer = $document->loadRenderer('modules');
					$position = "menu-interno-busca";
					$options = array('style' => 'raw');
					echo $renderer->render($position, $options, null);

					?>

				</div>
			</div>
		</div>
	</div>
</div>