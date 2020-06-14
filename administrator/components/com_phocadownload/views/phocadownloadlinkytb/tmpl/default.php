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
function insertLink() {
	
	var urlOutput;
	var url = document.getElementById("url").value;
	if (url != '' ) {
		urlOutput = "|url="+url;
	}

	if (urlOutput != '' && urlOutput) {
		var tag = "{phocadownload view=youtube"+urlOutput+"}";
		window.parent.jInsertEditorText(tag, '<?php echo $this->t['ename']; ?>');
		//window.parent.document.getElementById('sbox-window').close();
		window.parent.SqueezeBox.close();
		return false;
	} else {
		alert("<?php echo JText::_( 'COM_PHOCADOWNLOAD_WARNING_SET_YOUTUBE_URL', true ); ?>");
		return false;
	}
}
</script>
<div id="phocadownload-links">
<fieldset class="adminform">
<legend><?php echo JText::_( 'COM_PHOCADOWNLOAD_YOUTUBE_VIDEO' ); ?></legend>
<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">
	
	
	<tr >
		<td class="key" align="right" >
			<label for="url">
				<?php echo JText::_( 'COM_PHOCADOWNLOAD_YOUTUBE_URL' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="url" name="url" />
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