<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');

$app		= JFactory::getApplication();
$db			= JFactory::getDBO();
$user 		= JFactory::getUser();
$config		= JFactory::getConfig();
$nullDate 	= $db->getNullDate();
$now		= JFactory::getDate();

echo '<div id="phocadownload-upload"><div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';

if ($this->t['displayupload'] == 1) {


?>
<script type="text/javascript">
Joomla.submitbutton = function(task, id)
{
	if (id > 0) {
		document.getElementById('adminForm').actionid.value = id;
	}
	Joomla.submitform(task, document.getElementById('adminForm'));

}
</script>



<h4><?php echo JText::_( 'COM_PHOCADOWNLOAD_UPLOADED_FILES' ); ?></h4>
<?php
if ($this->t['catidfiles'] == 0 || $this->t['catidfiles'] == '') {
	echo '<div class="alert alert-error">'.JText::_('COM_PHOCADOWNLOAD_PLEASE_SELECT_CATEGORY_TO_BE_ABLE_TO_UPLOAD_FILES').'</div>';
}
?>
<form action="<?php echo htmlspecialchars($this->t['action']);?>" method="post" name="phocadownloadfilesform" id="adminForm">

<div class="filter-search btn-group pull-left">
	<label for="filter_search" class="element-invisible"><?php echo JText::_( 'COM_PHOCADOWNLOAD_FILTER' ); ?></label>
	<input type="text" name="search" id="pdsearch" placeholder="<?php echo JText::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>" value="<?php echo $this->t['listsfiles']['search'];?>" title="<?php echo JText::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>" />
</div>

<div class="btn-group pull-left hidden-phone">
	<button class="btn tip hasTooltip" type="submit" onclick="this.form.submit();"  title="<?php echo JText::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>"><i class="icon-search"></i></button>
	<button class="btn tip hasTooltip" type="button" onclick="document.getElementById('pdsearch').value='';document.phocadownloadfilesform.submit();" title="<?php echo JText::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>"><i class="icon-remove"></i></button>
</div>

<div style="float:right">
<?php echo $this->t['listsfiles']['catid'] ?>
</div>
<div class="clearfix ph-cb"></div>

<table class="adminlist">
<thead>
	<tr>
	<th class="title" width="50%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_TITLE', 'a.title', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image'); ?></th>
	<th width="3%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_PUBLISHED', 'a.published', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>
	<th width="3%" nowrap="nowrap"><?php echo JText::_('COM_PHOCADOWNLOAD_DELETE'); ?></th>
	<th width="3%" nowrap="nowrap"><?php echo JText::_('COM_PHOCADOWNLOAD_ACTIVE'); ?></th>
	<th width="3%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_PHOCADOWNLOAD_APPROVED', 'a.approved', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>

	<th width="3%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_PHOCADOWNLOAD_DATE_UPLOAD', 'a.date', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>


	<th width="3%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_PHOCADOWNLOAD_CATEGORY', 'a.catid', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>

</thead>

<tbody><?php
$k 		= 0;
$i 		= 0;
$n 		= count( $this->t['filesitems'] );
$rows 	= &$this->t['filesitems'];
if (is_array($rows)) {
	foreach ($rows as $row) {

	// USER RIGHT - Delete (Publish/Unpublish) - - - - - - - - - - -
	// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
	// because we cannot check the access and delete in one time
	$user = JFactory::getUser();
	$rightDisplayDelete	= 0;
	$catAccess	= PhocaDownloadAccess::getCategoryAccessByFileId((int)$row->id);

	if (!empty($catAccess)) {
		$rightDisplayDelete = PhocaDownloadAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
	}
	// - - - - - - - - - - - - - - - - - - - - - -

	?><tr class="<?php echo "row$k"; ?>">

	<td><?php
	$icon = PhocaDownloadFile::getMimeTypeIcon($row->filename);
	echo $icon . ' ' . $row->title;
	?></td>

	<?php

	// Publish Unpublish
	echo '<td align="center">';
	if ($row->published == 1) {
		if ($rightDisplayDelete) {
			echo '<a href="javascript:void(0)" onclick="javascript:Joomla.submitbutton(\'unpublish\', '.(int)$row->id.');" >';
			echo JHTML::_('image', $this->t['pi'].'icon-publish.png', JText::_('COM_PHOCADOWNLOAD_PUBLISHED'));
			echo '</a>';
		} else {
			echo JHTML::_('image', $this->t['pi'].'icon-publish-g.png', JText::_('COM_PHOCADOWNLOAD_PUBLISHED'));
		}
	}
	if ($row->published == 0) {
		if ($rightDisplayDelete) {
			echo '<a href="javascript:void(0)" onclick="javascript:Joomla.submitbutton(\'publish\', '.(int)$row->id.');" >';
			echo JHTML::_('image', $this->t['pi'].'icon-unpublish.png', JText::_('COM_PHOCADOWNLOAD_UNPUBLISHED'));
			echo '</a>';
		} else {
			echo JHTML::_('image', $this->t['pi'].'icon-unpublish-g.png', JText::_('COM_PHOCADOWNLOAD_UNPUBLISHED'));
		}
	}
	echo '</td>';

	echo '<td align="center">';
	if ($rightDisplayDelete) {
		echo '<a href="javascript:void(0)" onclick="javascript: if (confirm(\''.JText::_('COM_PHOCADOWNLOAD_WARNING_DELETE_ITEMS').'\')) {Joomla.submitbutton(\'delete\', '.(int)$row->id.');}" >';
		echo JHTML::_('image', $this->t['pi'].'icon-trash.png', JText::_('COM_PHOCADOWNLOAD_DELETE'));
		echo '</a>';
	} else {
		echo JHTML::_('image', $this->t['pi'].'icon-trash-g.png', JText::_('COM_PHOCADOWNLOAD_DELETE'));
	}
	echo '</td>';

	echo '<td align="center">';
	// User should get info about active/not active file (if e.g. admin change the active status)
	$publish_up 	= JFactory::getDate($row->publish_up);
	$publish_down 	= JFactory::getDate($row->publish_down);
	$tz 			= new DateTimeZone($config->get('offset'));
	$publish_up->setTimezone($tz);
	$publish_down->setTimezone($tz);


	if ( $now->toUnix() <= $publish_up->toUnix() ) {
		$text = JText::_( 'COM_PHOCADOWNLOAD_PENDING' );
	} else if ( ( $now->toUnix() <= $publish_down->toUnix() || $row->publish_down == $nullDate ) ) {
		$text = JText::_( 'COM_PHOCADOWNLOAD_ACTIVE' );
	} else if ( $now->toUnix() > $publish_down->toUnix() ) {
		$text = JText::_( 'COM_PHOCADOWNLOAD_EXPIRED' );
	}

	$times = '';
	if (isset($row->publish_up)) {
		if ($row->publish_up == $nullDate) {
			$times .= "\n".JText::_( 'COM_PHOCADOWNLOAD_START') . ': '.JText::_( 'COM_PHOCADOWNLOAD_ALWAYS' );
		} else {
			$times .= "\n".JText::_( 'COM_PHOCADOWNLOAD_START') .": ". $publish_up->format("D, d M Y H:i:s");
		}
	}
	if (isset($row->publish_down)) {
		if ($row->publish_down == $nullDate) {
			$times .= "\n". JText::_( 'COM_PHOCADOWNLOAD_FINISH'). ': '. JText::_('COM_PHOCADOWNLOAD_NO_EXPIRY' );
		} else {
			$times .= "\n". JText::_( 'COM_PHOCADOWNLOAD_FINISH') .": ". $publish_up->format("D, d M Y H:i:s");
		}
	}

	if ( $times ) {
		echo '<span class="editlinktip hasTip" title="'. JText::_( 'COM_PHOCADOWNLOAD_PUBLISH_INFORMATION' ).': '. $times.'">'
			.'<a href="javascript:void(0);" >'. $text.'</a></span>';
	}


	echo '</td>';

	// Approved
	echo '<td align="center">';
	if ($row->approved == 1) {
		echo JHTML::_('image', $this->t['pi'].'icon-publish.png', JText::_('COM_PHOCADOWNLOAD_APPROVED'));
	} else {
		echo JHTML::_('image', $this->t['pi'].'icon-unpublish.png', JText::_('COM_PHOCADOWNLOAD_NOT_APPROVED'));
	}
	echo '</td>';

	$upload_date = JFactory::getDate($row->date);
	$upload_date->setTimezone($tz);
	echo '<td align="center">'. $upload_date .'</td>';

	//echo '<td align="center">'. $row->date .'</td>';


	echo '<td align="center">'. $row->categorytitle .'</td>'
	//echo '<td align="center">'. $row->id .'</td>'
	.'</tr>';

		$k = 1 - $k;
		$i++;
	}
}
?></tbody>
<tfoot>
	<tr>
	<td colspan="7" class="footer"><?php

//$this->t['filespagination']->setTab($this->t['currenttab']['files']);
if (!empty($this->t['filesitems'])) {
	echo '<div class="pd-center pagination">';
	echo '<div class="pd-inline">';

	echo '<div style="margin:0 10px 0 10px;display:inline;">'
		.JText::_('COM_PHOCADOWNLOAD_DISPLAY_NUM') .'&nbsp;'
		.$this->t['filespagination']->getLimitBox()
		.'</div>';
	echo '<div class="sectiontablefooter'.$this->t['p']->get( 'pageclass_sfx' ).'" style="margin:0 10px 0 10px;display:inline;" >'
		.$this->t['filespagination']->getPagesLinks()
		.'</div>';
	echo '<div class="pagecounter" style="margin:0 10px 0 10px;display:inline;">'
		.$this->t['filespagination']->getPagesCounter()
		.'</div>';
	echo '</div></div>';
}




?></td>
	</tr>
</tfoot>
</table>


<?php echo JHTML::_( 'form.token' ); ?>

<input type="hidden" name="controller" value="user" />
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="user"/>
<input type="hidden" name="actionid" value=""/>
<input type="hidden" name="tab" value="<?php echo $this->t['currenttab']['files'];?>" />
<input type="hidden" name="limitstart" value="<?php echo $this->t['filespagination']->limitstart;?>" />
<input type="hidden" name="Itemid" value="<?php echo $app->input->get('Itemid', 0, 'int') ?>"/>
<input type="hidden" name="filter_order" value="<?php echo $this->t['listsfiles']['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />

</form>

<?php

// Upload
$currentFolder = '';
if (isset($this->state->folder) && $this->state->folder != '') {
	$currentFolder = $this->state->folder;
}
?>
<h4><?php
	echo JText::_( 'COM_PHOCADOWNLOAD_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCADOWNLOAD_MAX_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread'].']';
?></h4>

<?php
if ($this->t['errorcatid'] != '') {
	echo '<div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->t['errorcatid'] . '</div>';
} ?>

<form onsubmit="return OnUploadSubmitFile();" action="<?php echo $this->t['actionamp'] ?>task=upload&amp;<?php echo $this->t['session']->getName().'='.$this->t['session']->getId(); ?>&amp;<?php echo JSession::getFormToken();?>=1" name="phocadownloaduploadform" id="phocadownload-upload-form" method="post" enctype="multipart/form-data">
<table>
	<tr>
		<td><strong><?php echo JText::_('COM_PHOCADOWNLOAD_FILENAME');?>:</strong></td><td>
			<input type="file" id="file-upload" class="phfileuploadcheckcat" name="Filedata" />
			<button class="btn btn-primary" id="file-upload-submit"><i class="icon-upload icon-white"></i><?php echo JText::_('COM_PHOCADOWNLOAD_START_UPLOAD')?></button>
			<span id="upload-clear"></span></td>
		</tr>

		<?php
		if ($this->t['errorfile'] != '') {
			echo '<tr><td></td><td><div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->t['errorfile'] . '</div></td></tr>';
		} ?>

		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCADOWNLOAD_FILE_TITLE' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-title" name="phocadownloaduploadtitle" value="<?php echo $this->t['formdata']->title ?>"  maxlength="255" class="comment-input" /></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCADOWNLOAD_DESCRIPTION' ); ?>:</strong></td>
			<td><textarea id="phocadownload-upload-description" name="phocadownloaduploaddescription" onkeyup="countCharsUpload();" cols="30" rows="10" class="comment-input"><?php echo $this->t['formdata']->description ?></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo JText::_('COM_PHOCADOWNLOAD_CHARACTERS_WRITTEN');?> <input name="phocadownloaduploadcountin" value="0" readonly="readonly" class="comment-input2" /> <?php echo JText::_('COM_PHOCADOWNLOAD_AND_LEFT_FOR_DESCRIPTION');?> <input name="phocadownloaduploadcountleft" value="<?php echo $this->t['maxuploadchar'];?>" readonly="readonly" class="comment-input2" />
			</td>
		</tr>

		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCADOWNLOAD_AUTHOR' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-author" name="phocadownloaduploadauthor" value="<?php echo $this->t['formdata']->author ?>"  maxlength="255" class="comment-input" /></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCADOWNLOAD_AUTHOR_EMAIL' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-email" name="phocadownloaduploademail" value="<?php echo $this->t['formdata']->email ?>"  maxlength="255" class="comment-input" /></td>
		</tr>

		<?php
		if ($this->t['erroremail'] != '') {
			echo '<tr><td></td><td><div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->t['erroremail'] . '</div></td></tr>';
		} ?>

		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCADOWNLOAD_AUTHOR_WEBSITE' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-website" name="phocadownloaduploadwebsite" value="<?php echo $this->t['formdata']->website ?>"  maxlength="255" class="comment-input" /></td>
		</tr>

		<?php
		if ($this->t['errorwebsite'] != '') {
			echo '<tr><td></td><td><div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->t['errorwebsite'] . '</div></td></tr>';
		} ?>

		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCADOWNLOAD_LICENSE' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-license" name="phocadownloaduploadlicense" value="<?php echo $this->t['formdata']->license ?>"  maxlength="255" class="comment-input" /></td>
		</tr>

		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCADOWNLOAD_VERSION' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-version" name="phocadownloaduploadversion" value="<?php echo $this->t['formdata']->version ?>"  maxlength="255" class="comment-input" /></td>
		</tr>

	</table>

	<ul class="upload-queue" id="upload-queue"><li style="display: none" ></li></ul>

	<?php /*<input type="hidden" name="controller" value="user" /> */ ?>
	<input type="hidden" name="viewback" value="user" />
	<input type="hidden" name="view" value="user"/>
	<input type="hidden" name="task" value="upload"/>
	<input type="hidden" name="tab" value="<?php echo $this->t['currenttab']['files'];?>" />
	<input type="hidden" name="Itemid" value="<?php echo $app->input->get('Itemid', 0, 'int') ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->t['listsfiles']['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="catidfiles" value="<?php echo $this->t['catidfiles'] ?>"/>
</form>
<div id="loading-label-file"><div style="text-align:center"><?php echo JHTML::_('image', $this->t['pi'].'icon-loading.gif', '') . JText::_('COM_PHOCADOWNLOAD_LOADING'); ?></div></div>

	<?php
}
echo '</div>';

?>
