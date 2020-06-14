<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );

$ext 	= PhocaDownloadFile::getExtension( $this->_tmp_file->path_without_name_relative );
$group 	= PhocaDownloadSettings::getManagerGroup($this->manager);


if ($this->manager == 'filemultiple') {
	$checked 	= JHTML::_('grid.id', $this->filei + count($this->folders), $this->files[$this->filei]->path_with_name_relative_no );
	
	$icon		= PhocaDownloadFile::getMimeTypeIcon($this->_tmp_file->name);
	echo '<tr>'
	.' <td>'. $checked .'</td>'
	.' <td class="ph-img-table">'
	. $icon .'</a></td>'
	.' <td>' . $this->_tmp_file->name . '</td>'
	.'</tr>';
	
	
} else {
	if (($group['i'] == 1) && ($ext == 'png' || $ext == 'jpg' || $ext == 'gif' || $ext == 'jpeg') ) {
		
		echo '<tr>'
		.'<td></td>'
		.'<td>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">'
		. JHTML::_( 'image', str_replace( '../', '', $this->_tmp_file->path_without_name_relative), JText::_('COM_PHOCADOWNLOAD_INSERT'), array('title' => JText::_('COM_PHOCADOWNLOAD_INSERT_ICON')))
		.'</a>'
		.' <td>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' . $this->_tmp_file->path_with_name_relative_no.'\')">'
		. $this->_tmp_file->name
		.'</a>'
		.'</td>'
		.'</tr>';
	
	} else {
		
		echo '<tr>'
		.'<td></td>'
		.'<td>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">'
		. JHTML::_( 'image', $this->t['i'].'icon-file.png', '', JText::_('COM_PHOCADOWNLOAD_INSERT_FILENAME'))
		.'</a>'
		.' <td>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">'
		. $this->_tmp_file->name
		.'</a>'
		.'</td>'
		.'</tr>';
	}
}
?>
