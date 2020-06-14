<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$extlink 	= 0;
if (isset($this->item->extid) && $this->item->extid != '') {
	$extlink = 1;
}
$class		= $this->t['n'] . 'RenderAdminView';
$r 			=  new $class();

?>
<script type="text/javascript">
Joomla.submitbutton = function(task){
	if (task != '<?php echo $this->t['task'] ?>.cancel' && document.getElementById('jform_catid').value == '') {
		alert('<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true) . ' - '. JText::_($this->t['l'].'_ERROR_CATEGORY_NOT_SELECTED', true);?>');
	} else if (task == '<?php echo $this->t['task'] ?>.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
	else {
		Joomla.renderMessages({"error": ["<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>"]});
		<?php /* alert('<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>'); */ ?>
	}
}
</script><?php
echo $r->startForm($this->t['o'], $this->t['task'], $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span10 form-horizontal">';
$tabs = array (
'general' 		=> JText::_($this->t['l'].'_GENERAL_OPTIONS'),
'publishing' 	=> JText::_($this->t['l'].'_PUBLISHING_OPTIONS')
);
echo $r->navigation($tabs);

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n";
$formArray = array ('title', 'alias', 'catid', 'ordering','access');
echo $r->group($this->form, $formArray);
$formArray = array('description');
echo $r->group($this->form, $formArray, 1);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="publishing">'."\n";
foreach($this->form->getFieldset('publish') as $field) {
	echo '<div class="control-group">';
	if (!$field->hidden) {
		echo '<div class="control-label">'.$field->label.'</div>';
	}
	echo '<div class="controls">';
	echo $field->input;
	echo '</div></div>';
}
echo '</div>';

echo '</div>';//end tab content
echo '</div>';//end span10
// Second Column
echo '<div class="span2"></div>';//end span2

echo '<input type="hidden" name="jform[filename]" id="jform_filename" value="-" />'
	.'<input type="hidden" name="jform[textonly]" id="jform_textonly" value="1" />';
echo $r->formInputs();
echo $r->endForm();
?>
