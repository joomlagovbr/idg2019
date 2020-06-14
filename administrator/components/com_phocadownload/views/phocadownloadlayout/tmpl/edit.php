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
Joomla.submitbutton = function(task) {
	if (task == '<?php echo $this->t['task'] ?>.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
		<?php echo $this->form->getField('categories')->save(); ?>
		<?php echo $this->form->getField('category')->save(); ?>
		<?php echo $this->form->getField('file')->save(); ?>
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
echo '<div class="span8 form-horizontal">';
$tabs = array (
'general' 		=> JText::_($this->t['l'].'_GENERAL_OPTIONS')
);
echo $r->navigation($tabs);

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n"; 

$formArray = array('categories', 'category', 'file' );
echo $r->group($this->form, $formArray, 1);
echo '</div>'. "\n";
	
				
echo '</div>';//end tab content
echo '</div>';//end span10
// Second Column
echo '<div class="span4">';

echo '<div class="alert alert-error">' . JText::_('COM_PHOCADOWNLOAD_LAYOUT_WARNING').'</div>';
	
echo '<div class="alert alert-info"><h4>' . JText::_('COM_PHOCADOWNLOAD_CATEGORIES_VIEW').'</h4>';
$lP = PhocaDownloadSettings::getLayoutParams('categories');
echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_PARAMETERS').'</h3></div>';
if (isset($lP['search'])) {
	foreach ($lP['search'] as $k => $v) {
		echo $v . ' ';
	}
}
echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_STYLES').'</h3></div>';
if (isset($lP['style'])) {
	foreach ($lP['style'] as $k => $v) {
		echo $v . ' ';
	}
}
echo '</div>';

echo '<div class="alert alert-info"><h4>' . JText::_('COM_PHOCADOWNLOAD_CATEGORY_VIEW').'</h4>';
$lP = PhocaDownloadSettings::getLayoutParams('category');
echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_PARAMETERS').'</h3></div>';
if (isset($lP['search'])) {
	foreach ($lP['search'] as $k => $v) {
		echo $v . ' ';
	}
}
echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_STYLES').'</h3></div>';
if (isset($lP['style'])) {
	foreach ($lP['style'] as $k => $v) {
		echo $v . ' ';
	}
}
echo '</div>';

echo '<div class="alert alert-info"><h4>' . JText::_('COM_PHOCADOWNLOAD_FILE_VIEW').'</h4>';
$lP = PhocaDownloadSettings::getLayoutParams('file');
echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_PARAMETERS').'</h3></div>';
if (isset($lP['search'])) {
	foreach ($lP['search'] as $k => $v) {
		echo $v . ' ';
	}
}
echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_STYLES').'</h3></div>';
if (isset($lP['style'])) {
	foreach ($lP['style'] as $k => $v) {
		echo $v . ' ';
	}
}

echo '</div>';//end span2
echo $r->formInputs();
echo $r->endForm();
?>