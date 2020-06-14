<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class JFormFieldPhocaDownloadToken extends JFormField
{
	protected $type 		= 'PhocaDownloadToken';

	protected function getInput() {
		
		//PhocaDownlooadUtils::getToken()
		$salt = md5('string '. date('s'). mt_rand(0,9999) . str_replace(mt_rand(0,9), mt_rand(0,9999), date('r')). 'end string');
		$token = hash('sha256', $salt . time());
		
		// Initialize variables.
		$html = array();
		
		// Initialize some field attributes.
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		
		$maptype	= ( (string)$this->element['maptype'] ? $this->element['maptype'] : '' );

		// Initialize JavaScript field attributes.
		$onchange = (string) $this->element['onchange'];
		$onchangeOutput = ' onChange="'.(string) $this->element['onchange'].'"';


		
		$html[] = '<div class="input-append">';
		$html[] = '<input type="text" id="'.$this->id.'_id" name="'.$this->name.'" value="'. $this->value.'"' .
					' '.$class.$size.$disabled.$readonly.$onchangeOutput.$maxLength.' />';
		$html[] = '<a class="btn" title="'.JText::_('COM_PHOCADOWNLOAD_SET_TOKEN').'"'
					.' href="javascript:void(0);"'
					.' onclick="javascript:document.getElementById(\''.$this->id.'_id\').value = \''.$token.'\';return true;">'
					. JText::_('COM_PHOCADOWNLOAD_SET_TOKEN').'</a>';
		$html[] = '</div>'. "\n";
		return implode("\n", $html);
	
	}
}
?>