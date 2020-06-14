<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
$rightDisplay	= 0;
if (!empty($this->t['file'][0])) {
	$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $this->t['file'][0]->cataccessuserid, $this->t['file'][0]->cataccess, $this->t['user']->getAuthorisedViewLevels(), $this->t['user']->get('id', 0), 0);
}
// - - - - - - - - - - - - - - - - - - - - - -

if ($rightDisplay == 1) {

	if ($this->t['html5_play'] == 1 && $this->t['filetype'] != 'flv') {
		if ($this->t['filetype'] == 'mp3') {
			echo '<audio width="'.$this->t['playerwidth'].'" height="'.$this->t['playerheight'].'" style="margin-top: 10px;" controls>';
			echo '<source src="'.$this->t['playfilewithpath'].'" type="video/mp4">';
			echo JText::_('COM_PHOCADOWNLOAD_BROWSER_DOES_NOT_SUPPORT_AUDIO_VIDEO_TAG');
			echo '</audio>'. "\n";
		} else if ($this->t['filetype'] == 'mp4') {
			echo '<video width="'.$this->t['playerwidth'].'" height="'.$this->t['playerheight'].'" style="margin-top: 10px;" controls>';
			echo '<source src="'.$this->t['playfilewithpath'].'" type="video/mp4">';
			echo JText::_('COM_PHOCADOWNLOAD_BROWSER_DOES_NOT_SUPPORT_AUDIO_VIDEO_TAG');
			echo '</video>'. "\n";
		} else if ($this->t['filetype'] == 'ogg') {
			echo '<audio width="'.$this->t['playerwidth'].'" height="'.$this->t['playerheight'].'" style="margin-top: 10px;" controls>';
			echo '<source src="'.$this->t['playfilewithpath'].'" type="audio/ogg">';
			echo JText::_('COM_PHOCADOWNLOAD_BROWSER_DOES_NOT_SUPPORT_AUDIO_VIDEO_TAG');
			echo '</audio>'. "\n";
		} else if ($this->t['filetype'] == 'ogv') {
			echo '<video width="'.$this->t['playerwidth'].'" height="'.$this->t['playerheight'].'" style="margin-top: 10px;" controls>';
			echo '<source src="'.$this->t['playfilewithpath'].'" type="video/ogg">';
			echo JText::_('COM_PHOCADOWNLOAD_BROWSER_DOES_NOT_SUPPORT_AUDIO_VIDEO_TAG');
			echo '</video>'. "\n";
		} /* else if ($this->t['filetype'] == 'flv') {
            echo '<video width="'.$this->t['playerwidth'].'" height="'.$this->t['playerheight'].'" style="margin-top: 10px;" controls>';
            echo '<source src="'.$this->t['playfilewithpath'].'" type="video/x-flv">';
            echo JText::_('COM_PHOCADOWNLOAD_BROWSER_DOES_NOT_SUPPORT_AUDIO_VIDEO_TAG');
            echo '</video>'. "\n";
        }*/


	} else {

	//Flow Player
	$versionFLP 	= '3.2.2';
	$versionFLPJS 	= '3.2.2';
	$document = JFactory::getDocument();
	$document->addScript($this->t['playerpath'].'flowplayer-'.$versionFLPJS.'.min.js');

	?>
	<div style="text-align:center;">
	<div style="margin: 10px auto;text-align:center; width:<?php echo $this->t['playerwidth']; ?>px"><a href="<?php echo $this->t['playfilewithpath']; ?>"  style="display:block;width:<?php echo $this->t['playerwidth']; ?>px;height:<?php echo $this->t['playerheight']; ?>px" id="player"></a><?php

	if ($this->t['filetype'] == 'mp3') {
		?><script>

		flowplayer("player", "<?php echo $this->t['playerpath']; ?>flowplayer-<?php echo $versionFLP ?>.swf",
		{
			plugins: {
				controls: {
					fullscreen: false,
					height: <?php echo $this->t['playerheight']; ?>
				}
			}
		}
		);</script><?php
	} else {
		?><script>

		flowplayer("player", "<?php echo $this->t['playerpath']; ?>flowplayer-<?php echo $versionFLP ?>.swf");</script><?php
	}
	?></div></div><?php
	}
} else {
	echo JText::_('COM_PHOCADOWNLOAD_NO_RIGHTS_ACCESS_CATEGORY');
}


