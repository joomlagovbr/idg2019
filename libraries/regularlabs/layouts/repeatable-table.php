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
 * @var JForm  $tmpl            The Empty form for template
 * @var array  $forms           Array of JForm instances for render the rows
 * @var bool   $multiple        The multiple state for the form field
 * @var int    $min             Count of minimum repeating in multiple mode
 * @var int    $max             Count of maximum repeating in multiple mode
 * @var string $fieldname       The field name
 * @var string $control         The forms control
 * @var string $label           The field label
 * @var string $description     The field description
 * @var array  $buttons         Array of the buttons that will be rendered
 * @var bool   $groupByFieldset Whether group the subform fields by it`s fieldset
 */

extract($displayData);

// Add script
if ($multiple)
{
	JHtml::_('jquery.ui', ['core', 'sortable']);
	JHtml::_('script', 'system/subform-repeatable.js', ['version' => 'auto', 'relative' => true]);
}

// Build heading
$fields = $tmpl->getGroup('');

$column_count = 4;

$table_head   = [];
$table_head[] = '<th style="width:10%;">' . strip_tags($fields[$id . '__' . $fieldname . 'X__field']->label) . '</th>';
if (isset($fields[$id . '__' . $fieldname . 'X__field_comparison']))
{
	$table_head[] = '<th style="width:10%;">' . strip_tags($fields[$id . '__' . $fieldname . 'X__field_comparison']->label) . '</th>';
	$column_count++;
}
$table_head[] = '<th>' . strip_tags($fields[$id . '__' . $fieldname . 'X__field_value']->label) . '</th>';

$sublayout = 'section';

// Label will not be shown for sections layout, so reset the margin left
JFactory::getDocument()->addStyleDeclaration(
	'.subform-table-sublayout-section .controls { margin-left: 0px }'
);
?>
<div class="row-fluid">
	<div class="subform-repeatable-wrapper subform-table-layout subform-table-sublayout-<?php echo $sublayout; ?> form-vertical">
		<div
				class="subform-repeatable"
				data-bt-add="a.group-add-<?php echo $unique_subform_id; ?>"
				data-bt-remove="a.group-remove-<?php echo $unique_subform_id; ?>"
				data-bt-move="a.group-move-<?php echo $unique_subform_id; ?>"
				data-repeatable-element="tr.subform-repeatable-group-<?php echo $unique_subform_id; ?>"
				data-rows-container="tbody.rows-container-<?php echo $unique_subform_id; ?>"
				data-minimum="<?php echo $min; ?>" data-maximum="<?php echo $max; ?>"
		>
			<table class="adminlist table table-striped ">
				<thead>
					<tr>
						<th style="width:1%;"></th>

						<?php echo implode('', $table_head); ?>

						<th style="width:1%;"></th>
					</tr>
				</thead>
				<tbody class="rows-container-<?php echo $unique_subform_id; ?>">
					<?php foreach ($forms as $k => $form):
						echo $this->sublayout(
							$sublayout,
							[
								'form'              => $form,
								'basegroup'         => $id . '__' . $fieldname,
								'group'             => $id . '__' . $fieldname . $k,
								'buttons'           => $buttons,
								'unique_subform_id' => $unique_subform_id,
							]
						);
					endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="<?php echo $column_count; ?>">
							<div class="btn-group btn-group-full">
								<a
										class="btn btn-mini btn-full button btn-success group-add group-add-<?php echo $unique_subform_id; ?>"
										aria-label="<?php echo JText::_('JGLOBAL_FIELD_ADD'); ?>"
								>
									<span class="icon-plus" aria-hidden="true"></span>
								</a>
							</div>
						</th>
					</tr>
				</tfoot>
			</table>

			<?php if ($multiple) : ?>
				<template class="subform-repeatable-template-section"><?php echo trim(
						$this->sublayout(
							$sublayout,
							[
								'form'              => $tmpl,
								'basegroup'         => $id . '__' . $fieldname,
								'group'             => $id . '__' . $fieldname . 'X',
								'buttons'           => $buttons,
								'unique_subform_id' => $unique_subform_id,
							]
						)
					); ?></template>
			<?php endif; ?>
		</div>
	</div>
</div>
