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

$class		= $this->t['n'] . 'RenderAdminView';
$r 			=  new $class();

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == '<?php echo $this->t['task'] ?>.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
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
'publishing' 	=> JText::_($this->t['l'].'_PUBLISHING_OPTIONS'));
echo $r->navigation($tabs);

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n";

if ($this->ftp) { echo $this->loadTemplate('ftp');}

//$formArray = array ('title', 'type', 'filename', 'ordering');
//echo $r->group($this->form, $formArray);

echo '<div class="control-group">';
echo $r->item($this->form, 'title');
echo $this->form->getInput('type');
echo $r->item($this->form, 'typeoutput');
echo $r->item($this->form, 'filename', $this->t['ssuffixtype']);
echo $r->item($this->form, 'ordering');

echo '</div>';

echo '<div class="clr"></div>';
echo $this->form->getLabel('source');
echo '<div class="clr"></div>';
echo '<div class="editor-border" id="ph-editor">';
echo $this->form->getInput('source');
echo '</div>';

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

// Second Column
echo '<div class="span2"></div>';//end span2
echo $r->formInputs();
echo $r->endForm();
?>