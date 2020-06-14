<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>



<form action="index.php" method="post" name="adminForm">

	<div class="col60">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>

				<table class="admintable">
				<?php
				foreach ($this->t['items'] as $value) {

					echo '<tr>';
					echo '<td class="key">';
					echo '<label for="'.$value->title.'" width="100" title="'.JText::_($value->title . ' DESC').'">';
					echo JText::_($value->title);
					echo '</label>';
					echo '</td>';

					echo '<td colspan="2">';

					switch ($value->type) {
						case 'textarea':
							echo PhocaDownloadHelper::getTextareaSettings($value->id, $value->title, $value->value);
						break;

						case 'textareaeditor':
							echo PhocaDownloadHelper::getTextareaEditorSettings($value->id, $value->title, $value->value);
						break;

						case 'select':
							echo PhocaDownloadHelper::getSelectSettings($value->id, $value->title, $value->value, $value->values);
						break;


						case 'text':
						default:
							if ($value->title == 'absolute_path') {
								echo '<div style="color:red;font-weight:bold">' . JText::_('Experts only!'). '</div>';
								echo '<div>' . JText::_('Root Path') . ': ' . JPATH_ROOT . '</div>';
							}
							echo PhocaDownloadHelper::getTextSettings($value->id, $value->title, $value->value);
						break;

					}
					echo '</td>';
					echo '</tr>';

				}
				?>

			</table>
		</fieldset>
	</div>
	<div class="clearfix"></div>

	<input type="hidden" name="option" value="com_phocadownload" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="phocadownloadset" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>


