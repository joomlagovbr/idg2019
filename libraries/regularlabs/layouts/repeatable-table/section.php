<?php
/**
 * @package         Regular Labs Library
 * @version         20.3.22179
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * -----------------
 * @var array  $displayData
 * @var JForm  $form      The form instance for render the section
 * @var string $basegroup The base group name
 * @var string $group     Current group name
 * @var array  $buttons   Array of the buttons that will be rendered
 */
extract($displayData);

$fields = $form->getGroup('');

?>

<tr
		class="subform-repeatable-group subform-repeatable-group-<?php echo $unique_subform_id; ?>"
		data-base-name="<?php echo $basegroup; ?>"
		data-group="<?php echo $group; ?>"
>
	<td>
		<a class="sortable-handler group-move group-move-<?php echo $unique_subform_id; ?>" style="cursor: move;" aria-label="<?php echo JText::_('JGLOBAL_FIELD_MOVE'); ?>">
			<span class="icon-menu" aria-hidden="true"></span>
		</a>
	</td>
	<td data-column="<?php echo strip_tags($fields[$group . '__field']->label); ?>">
		<?php echo $fields[$group . '__field']->renderField(['hiddenLabel' => true]); ?>
		<?php if (isset($fields[$group . '__field_name'])) : ?>
			<?php echo $fields[$group . '__field_name']->renderField(['hiddenLabel' => true]); ?>
		<?php endif; ?>
	</td>
	<?php if (isset($fields[$group . '__field_comparison'])) : ?>
		<td data-column="<?php echo strip_tags($fields[$group . '__field_comparison']->label); ?>">
			<?php echo $fields[$group . '__field_comparison']->renderField(['hiddenLabel' => true]); ?>
		</td>
	<?php endif; ?>
	<td data-column="<?php echo strip_tags($fields[$group . '__field_value']->label); ?>">
		<?php echo $fields[$group . '__field_value']->renderField(['hiddenLabel' => true]); ?>
	</td>

	<td>
		<div class="btn-group">
			<a class="btn btn-mini button btn-danger group-remove group-remove-<?php echo $unique_subform_id; ?>" aria-label="<?php echo JText::_('JGLOBAL_FIELD_REMOVE'); ?>">
				<span class="icon-minus" aria-hidden="true"></span>
			</a>
		</div>
	</td>
</tr>
