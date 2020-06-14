<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
$user 	= JFactory::getUser();

//Ordering allowed ?
$ordering = ($this->t['lists']['order'] == 'a.ordering');

JHTML::_('behavior.tooltip');

if ($this->t['type'] == 0) {
	$view = 'file';
} else if ($this->t['type'] == 1) {
	$view = 'fileplaylink';
}  else if ($this->t['type'] == 2) {
	$view = 'fileplay';
}  else if ($this->t['type'] == 3) {
	$view = 'filepreviewlink';
} else if ($this->t['type'] == 4) {
	$view = 'filelist';
}


?>
<script type="text/javascript">
//<![CDATA[
function insertLink() {
	var title = document.getElementById("title").value;
	if (title != '') {
		title = "|text="+title;
	}
	<?php if ($this->t['type'] == 0) { ?>
	var target = document.getElementById("target").value;
	if (target != '') {
		target = "|target="+target;
	}
	<?php } else if ($this->t['type'] == 1 || $this->t['type'] == 2) { ?>
	var playerwidth = document.getElementById("playerwidth").value;
	if (playerwidth != '') {
		playerwidth = "|playerwidth="+playerwidth;
	}
	var playerheight = document.getElementById("playerheight").value;
	if (playerheight != '') {
		playerheight = "|playerheight="+playerheight;
	}
	var playerheightmp3 = document.getElementById("playerheightmp3").value;
	if (playerheightmp3 != '') {
		playerheightmp3 = "|playerheightmp3="+playerheightmp3;
	}
	<?php } else if ($this->t['type'] == 3) { ?>
	var previewwidth = document.getElementById("previewwidth").value;
	if (previewwidth != '') {
		previewwidth = "|previewwidth="+previewwidth;
	}
	var previewheight = document.getElementById("previewheight").value;
	if (previewheight != '') {
		previewheight = "|previewheight="+previewheight;
	}

	<?php } else if ($this->t['type'] == 4) { ?>
	var limit = document.getElementById("limit").value;
	if (limit != '') {
		limit = "|limit="+limit;
	}
	var categoryid = document.getElementById("catid").value;
	if (categoryid != '' && parseInt(categoryid) > 0) {
		categoryIdOutput = "|id="+categoryid;
	} else {
		categoryIdOutput = '';
	}

	<?php } ?>

	var fileIdOutput;
	fileIdOutput = '';
	len = document.getElementsByName("fileid").length;
	for (i = 0; i <len; i++) {
		if (document.getElementsByName('fileid')[i].checked) {
			fileid = document.getElementsByName('fileid')[i].value;
			if (fileid != '' && parseInt(fileid) > 0) {
				fileIdOutput = "|id="+fileid;
			} else {
				fileIdOutput = '';
			}
		}
	}

	if (fileIdOutput != '' &&  parseInt(fileid) > 0) {
		<?php if ($this->t['type'] == 0) { ?>
			var tag = "{phocadownload view=<?php echo $view ?>"+fileIdOutput+title+target+"}";
		<?php } else if ($this->t['type'] == 1) { ?>
			var tag = "{phocadownload view=<?php echo $view ?>"+fileIdOutput+title+playerwidth+playerheight+playerheightmp3+"}";
		<?php } else if ($this->t['type'] == 2) { ?>
			var tag = "{phocadownload view=<?php echo $view ?>"+fileIdOutput+title+playerwidth+playerheight+playerheightmp3+"}";
		<?php } else if ($this->t['type'] == 3) { ?>
			var tag = "{phocadownload view=<?php echo $view ?>"+fileIdOutput+title+previewwidth+previewheight+"}";
		<?php } else if ($this->t['type'] == 4) { ?>
			var tag = "{phocadownload view=<?php echo $view ?>"+fileIdOutput+limit+"}";
		<?php } ?>
		window.parent.jInsertEditorText(tag, '<?php echo htmlspecialchars($this->t['ename']); ?>');
		//window.parent.document.getElementById('sbox-window').close();
		window.parent.SqueezeBox.close();
		return false;
	} else {
		<?php if ($this->t['type'] == 4) { ?>

		if (categoryIdOutput != '' &&  parseInt(categoryid) > 0) {
			var tag = "{phocadownload view=<?php echo $view ?>"+categoryIdOutput+limit+"}";
			window.parent.jInsertEditorText(tag, '<?php echo htmlspecialchars($this->t['ename']); ?>');
			window.parent.SqueezeBox.close();
		} else {
			alert("<?php echo JText::_( 'COM_PHOCADOWNLOAD_YOU_MUST_SELECT_CATEGORY', true ); ?>");
			return false;
		}
		<?php } else { ?>
		alert("<?php echo JText::_( 'COM_PHOCADOWNLOAD_YOU_MUST_SELECT_FILE', true ); ?>");
		return false;
		<?php } ?>
	}
}
//]]>
</script>
<div id="phocadownload-links">
<fieldset class="adminform">

<legend><?php echo JText::_( 'COM_PHOCADOWNLOAD_FILE' ); ?></legend>
<form action="<?php echo $this->t['request_url']; ?>" method="post" name="adminForm" id="adminForm">
		<?php if ($this->t['type'] != 4) { ?>
		<table class="admintable" width="100%">
		<tr>
			<td class="key" align="right" width="20%">
				<label for="title">
					<?php echo JText::_( 'COM_PHOCADOWNLOAD_FILTER' ); ?>
				</label>
			</td>
			<td width="80%">
				<div class="input-append"><input type="text" name="search" id="search" value="<?php echo PhocaDownloadUtils::filterValue($this->t['lists']['search'], 'text');?>" class="text_area" onchange="document.adminForm.submit();" /> <button class="btn" onclick="this.form.submit();"><?php echo JText::_('COM_PHOCADOWNLOAD_FILTER'); ?></button> <button class="btn" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_PHOCADOWNLOAD_RESET'); ?></button></div>
			</td>
		</tr>
		<tr>
			<td class="key" align="right" nowrap="nowrap">
			<label for="title" nowrap="nowrap"><?php echo JText::_( 'COM_PHOCADOWNLOAD_CATEGORY' ); ?></label>
			</td>
			<td><?php echo $this->t['lists']['catid']; ?></td>
		</tr>
		</table>
		<?php } ?>

	<?php if ($this->t['type'] != 4) { ?>
	<div id="editcell">
		<table class="adminlist plg-button-tbl">
			<thead>
				<tr>
					<th width="5%"><?php echo JText::_( 'COM_PHOCADOWNLOAD_NUM' ); ?></th>
					<th width="5%"></th>
					<th class="title" width="60%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_TITLE', 'a.title', $this->t['lists']['order_Dir'], $this->t['lists']['order'] ); ?>
					</th>
					<th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_FILENAME', 'a.filename', $this->t['lists']['order_Dir'], $this->t['lists']['order'] ); ?>
					</th>
					<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_ID', 'a.id', $this->t['lists']['order_Dir'], $this->t['lists']['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5"><?php echo $this->t['pagination']->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->t['items'] ); $i < $n; $i++) {
					$row = &$this->t['items'][$i];



				?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->t['pagination']->getRowOffset( $i ); ?></td>
					<td><input type="radio" name="fileid" value="<?php echo $row->id ?>" /></td>

					<td><?php echo $row->title; ?></td>
					<td><?php echo $row->filename;?></td>
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
				}
			?>
			</tbody>
		</table>
	</div>
	<?php } ?>

<input type="hidden" name="controller" value="phocadownloadlinkfile" />
<input type="hidden" name="type" value="<?php echo $this->t['type']; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->t['lists']['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->t['lists']['order_Dir']; ?>" />
<input type="hidden" name="e_name" value="<?php echo $this->t['ename']?>" />
</form>


<?php

if ($this->t['type'] == 0) {
?>
<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">
	<tr >
		<td class="key" align="right">
			<label for="title">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_TITLE' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="title" name="title" />
		</td>
	</tr>
	<tr >
		<td class="key" align="right">
			<label for="target">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_TARGET' ); ?>
			</label>
		</td>
		<td>
			<select name="target" id="target">
			<option value="s" selected="selected"><?php echo JText::_( 'COM_PHOCADOWNLOAD_TARGET_SELF' ); ?></option>
			<option value="b"><?php echo JText::_( 'COM_PHOCADOWNLOAD_TARGET_BLANK' ); ?></option>
			<option value="t"><?php echo JText::_( 'COM_PHOCADOWNLOAD_TARGET_TOP' ); ?></option>
			<option value="p"><?php echo JText::_( 'COM_PHOCADOWNLOAD_TARGET_PARENT' ); ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td align="right"><button  class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCADOWNLOAD_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>

	<?php
} else if ($this->t['type'] == 1 || $this->t['type'] == 2){
	?>

<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">

	<?php if ($this->t['type'] == 1) { ?>
	<tr >
		<td class="key" align="right">
			<label for="title">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_TITLE' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="title" name="title" />
		</td>
	</tr>
	<?php } else { ?>
		<input type="hidden" id="title" name="title" />
	<?php }	?>

	<tr >
		<td class="key" align="right">
			<label for="playerwidth">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_PLAYER_WIDTH' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="playerwidth" name="playerwidth" value="328" />
		</td>
	</tr>

	<tr >
		<td class="key" align="right">
			<label for="playerheight">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_PLAYER_HEIGHT' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="playerheight" name="playerheight" value="200" />
		</td>
	</tr>

	<tr >
		<td class="key" align="right">
			<label for="playerheightmp3">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_PLAYER_HEIGHT_MP3' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="playerheightmp3" name="playerheightmp3" value="30" />
		</td>
	</tr>
	<?php if ($this->t['type'] == 1) { ?>
		<tr><td colspan="2"><?php echo JText::_('COM_PHOCADOWNLOAD_WARNING_PLAYER_SIZE')?></td></tr>
	<?php } ?>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><button  class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCADOWNLOAD_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>

	<?php
} else if ($this->t['type'] == 3){
	?>

<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">

	<?php if ($this->t['type'] == 1) { ?>
	<tr >
		<td class="key" align="right">
			<label for="title">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_TITLE' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="title" name="title" />
		</td>
	</tr>
	<?php } else { ?>
		<input type="hidden" id="title" name="title" />
	<?php }	?>

	<tr >
		<td class="key" align="right">
			<label for="previewwidth">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_PREVIEW_WIDTH' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="previewwidth" name="previewwidth" value="640" />
		</td>
	</tr>

	<tr >
		<td class="key" align="right">
			<label for="previewheight">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_PREVIEW_HEIGHT' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="previewheight" name="previewheight" value="480" />
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td align="right"><button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCADOWNLOAD_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>

	<?php
} else if ($this->t['type'] == 4){
	?>

<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">

	<tr>
		<td class="key" align="right" nowrap="nowrap">
		<label for="title" nowrap="nowrap"><?php echo JText::_( 'COM_PHOCADOWNLOAD_CATEGORY' ); ?></label>
		</td>
		<td><?php echo $this->t['lists']['catid']; ?></td>
	</tr>
	<tr >
		<td class="key" align="right">
			<label for="title">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_LIMIT' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="limit" name="limit" />
			<input type="hidden" id="title" name="title" />
		</td>
	</tr>




	<tr>
		<td>&nbsp;</td>
		<td align="right"><button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCADOWNLOAD_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>

	<?php
}
	?>
</fieldset>
<div style="text-align:left;"><span class="icon-16-edb-back"><a style="text-decoration:underline" href="<?php echo $this->t['backlink'];?>"><?php echo JText::_('COM_PHOCADOWNLOAD_BACK')?></a></span></div>
</div>
