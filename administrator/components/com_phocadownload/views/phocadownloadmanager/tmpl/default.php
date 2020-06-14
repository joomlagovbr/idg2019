<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$r 			=  new PhocaDownloadRenderAdminView();

if ($this->manager == 'filemultiple') {

	?><script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'phocadownloadm.cancel') {
			submitform(task);
		}

		if (task == 'phocadownloadm.save') {
			phocadownloadmform = document.getElementById('adminForm');
			if (phocadownloadmform.boxchecked.value==0) {
				alert( "<?php echo JText::_( 'COM_PHOCADOWNLOAD_WARNING_SELECT_FILENAME_OR_FOLDER', true ); ?>" );
			} else  {
				var f = phocadownloadmform;
				var nSelectedImages = 0;
				var nSelectedFolders = 0;
				var i=0;
				cb = eval( 'f.cb' + i );
				while (cb) {
					if (cb.checked == false) {
						// Do nothing
					}
					else if (cb.name == "cid[]") {
						nSelectedImages++;
					}
					else {
						nSelectedFolders++;
					}
					// Get next
					i++;
					cb = eval( 'f.cb' + i );
				}

				if (phocadownloadmform.jform_catid.value == "" && nSelectedImages > 0){
					alert( "<?php echo JText::_( 'COM_PHOCADOWNLOAD_WARNING_FILE_SELECTED_SELECT_CATEGORY', true ); ?>" );
				} else {
					submitform(task);
				}
			}
		}
		//submitform(task);
	}
	</script><?php
}

echo '<div id="phocadownloadmanager">';

if ($this->manager == 'filemultiple') {
	echo $r->startForm($this->t['o'], $this->t['task'], 'adminForm', 'adminForm');
	echo '<div class="span5 form-horizontal" style="border-right: 1px solid #d3d3d3;padding-right: 5px;">';
	echo '<h4>'. JText::_('COM_PHOCADOWNLOAD_MULTIPLE_ADD').'</h4>';

	echo '<div>'."\n";
	$formArray = array ('title', 'alias','published', 'approved', 'ordering', 'catid', 'language', 'pap_copy_m');
	echo $r->group($this->form, $formArray);
	echo '</div>'. "\n";

	echo '</div>'. "\n";
}

if ($this->manager == 'filemultiple') {
	echo '<div class="span7 form-horizontal">';
} else {
	echo '<div class="span12 form-horizontal">';
}

echo '<div class="pd-admin-path">' . JText::_('COM_PHOCADOWNLOAD_PATH'). ': '.JPath::clean($this->t['path']['orig_abs_ds']. $this->folderstate->folder) .'</div>';

//$countFaF =  count($this->images) + count($this->folders);
echo '<table class="table table-hover table-condensed ph-multiple-table">'
.'<thead>'
.'<tr>';
echo '<th class="hidden-phone ph-check">'. "\n";
if ($this->manager == 'filemultiple') {
	echo '<input type="checkbox" name="checkall-toggle" value="" title="'.JText::_('JGLOBAL_CHECK_ALL').'" onclick="Joomla.checkAll(this)" />'. "\n";
} else {
	echo '';
}
echo '</th>'. "\n";

echo '<th width="20">&nbsp;</th>'
.'<th width="95%">'.JText::_( $this->t['l'].'_FILENAME' ).'</th>'
.'</tr>'
.'</thead>';




/*
echo '<div class="pd-admin-files">';

if ($this->manager == 'filemultiple' && (count($this->files) > 0 || count($this->folders) > 0)) {
	echo '<div class="pd-admin-file-checkbox">';
	$fileFolders = count($this->files) + count($this->folders);
	echo '<input type="checkbox" name="toggle" value="" onclick="checkAll('.$fileFolders.');" />';
	echo '&nbsp;&nbsp;'. JText::_('COM_PHOCADOWNLOAD_CHECK_ALL');
	echo '</div>';
}*/
echo '<tbody>';
echo $this->loadTemplate('up');
if (count($this->files) > 0 || count($this->folders) > 0) { ?>
<div>

	<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
		$this->setFolder($i);
		$this->folderi = $i;
		echo $this->loadTemplate('folder');
	endfor; ?>

	<?php for ($i=0,$n=count($this->files); $i<$n; $i++) :
		$this->setFile($i);
		$this->filei = $i;
		echo $this->loadTemplate('file');
	endfor; ?>

</div>
<?php } else {
	echo '<tr>'
	.'<td>&nbsp;</td>'
	.'<td>&nbsp;</td>'
	.'<td>'.JText::_( $this->t['l'].'_THERE_IS_NO_FILE' ).'</td>'
	.'</tr>';
}
echo '</tbody>'
.'</table>';

if ($this->manager == 'filemultiple') {

	echo '<input type="hidden" name="task" value="" />'. "\n";
	echo '<input type="hidden" name="boxchecked" value="0" />'. "\n";
	echo '<input type="hidden" name="layout" value="edit" />'. "\n";
	echo JHtml::_('form.token');
	echo $r->endForm();

	echo '</div>';
	echo '<div class="clearfix"></div>';

} ?>

<div style="border-bottom:1px solid #cccccc;margin-bottom: 10px">&nbsp;</div>

<?php
if ($this->t['displaytabs'] > 0) {

	echo '<ul class="nav nav-tabs" id="configTabs">';

	$label = JHTML::_( 'image', $this->t['i'].'icon-16-upload.png','') . '&nbsp;'.JText::_($this->t['l'].'_UPLOAD');
	echo '<li><a href="#upload" data-toggle="tab">'.$label.'</a></li>';

	if((int)$this->t['enablemultiple']  > 0) {
		$label = JHtml::_( 'image', $this->t['i'].'icon-16-upload-multiple.png','') . '&nbsp;'.JText::_($this->t['l'].'_MULTIPLE_UPLOAD');
		echo '<li><a href="#multipleupload" data-toggle="tab">'.$label.'</a></li>';
	}

	$label = JHtml::_( 'image', $this->t['i'].'icon-16-folder.png','') . '&nbsp;'.JText::_($this->t['l'].'_CREATE_FOLDER');
	echo '<li><a href="#createfolder" data-toggle="tab">'.$label.'</a></li>';

	echo '</ul>';


	echo '<div class="tab-content">'. "\n";

	echo '<div class="tab-pane" id="upload">'. "\n";
	echo $this->loadTemplate('upload');
	echo '</div>'. "\n";
	echo '<div class="tab-pane" id="multipleupload">'. "\n";
	echo $this->loadTemplate('multipleupload');
	echo '</div>'. "\n";

	echo '<div class="tab-pane" id="createfolder">'. "\n";
	//echo PhocaDownloadFileUpload::renderCreateFolder($this->session->getName(), $this->session->getId(), $this->currentFolder, 'phocadownloadmanager', 'manager='.$this->manager.'&amp;tab='.$this->t['currenttab']['upload'].'&amp;field='. $this->field );
	echo PhocaDownloadFileUpload::renderCreateFolder($this->session->getName(), $this->session->getId(), $this->currentFolder, 'phocadownloadmanager', 'manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&amp;tab=createfolder&amp;field='. PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2') );
	echo '</div>'. "\n";

	echo '</div>'. "\n";
}
echo '</div>';

if ($this->t['tab'] != '') {$jsCt = 'a[href=#'.PhocaDownloadUtils::filterValue($this->t['tab'], 'alphanumeric') .']';} else {$jsCt = 'a:first';}
echo '<script type="text/javascript">';
echo '   jQuery(\'#configTabs '.$jsCt.'\').tab(\'show\');'; // Select first tab
echo '</script>';
?>
