<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');

$group 	= PhocaDownloadSettings::getManagerGroup($this->manager);

$link = 'index.php?option='.$this->t['o'].'&amp;view='. PhocaDownloadUtils::filterValue($this->t['task'], 'alphanumeric').'&amp;manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric'). $group['c'] .'&amp;folder='.PhocaDownloadUtils::filterValue($this->folderstate->parent, 'folderpath') .'&amp;field='. PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2');
echo '<tr><td>&nbsp;</td>'
.'<td class="ph-img-table">'
.'<a href="'.$link.'" >'
. JHTML::_( 'image', $this->t['i'].'icon-16-up.png', '').'</a>'
.'</td>'
.'<td><a href="'.$link.'" >..</a></td>'
.'</tr>';
