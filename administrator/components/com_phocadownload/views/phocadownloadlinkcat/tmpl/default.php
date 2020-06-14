<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<script type="text/javascript">
//<![CDATA[


function insertLink() {
	var title = document.getElementById("title").value;
	if (title != '') {
		title = "|text="+title;
	}
	var target = document.getElementById("target").value;
	if (target != '') {
		target = "|target="+target;
	}
	var categoryIdOutput;
	var categoryid = document.getElementById("catid").value;
	if (categoryid != '' && parseInt(categoryid) > 0) {
		categoryIdOutput = "|id="+categoryid;
	} else {
		categoryIdOutput = '';
	}

	if (categoryIdOutput != '' &&  parseInt(categoryid) > 0) {
		var tag = "{phocadownload view=category"+categoryIdOutput+title+target+"}";
		window.parent.jInsertEditorText(tag, '<?php echo $this->t['ename']; ?>');
		//window.parent.document.getElementById('sbox-window').close();
		window.parent.SqueezeBox.close();
		return false;
	} else {
		alert("<?php echo JText::_( 'COM_PHOCADOWNLOAD_YOU_MUST_SELECT_CATEGORY', true ); ?>");
		return false;
	}
}
//]]>
</script>

<div id="phocadownload-links">
<fieldset class="adminform">
<legend><?php echo JText::_( 'COM_PHOCADOWNLOAD_CATEGORY' ); ?></legend>
<form name="adminForm" id="adminForm">
<table class="admintable" width="100%">


	<tr >
		<td class="key" align="right" >
			<label for="title">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_CATEGORY' ); ?>
			</label>
		</td>
		<td>
			<?php echo $this->t['lists']['catid'];?>
		</td>
	</tr>


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
		<td align="right"><button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCADOWNLOAD_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>
</fieldset>
<div style="text-align:left;"><span class="icon-16-edb-back"><a style="text-decoration:underline" href="<?php echo $this->t['backlink'];?>"><?php echo JText::_('COM_PHOCADOWNLOAD_BACK')?></a></span></div>
</div>
