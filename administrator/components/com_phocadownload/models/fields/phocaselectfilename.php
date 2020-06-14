<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldPhocaSelectFilename extends JFormField
{
	public $type = 'PhocaSelectFilename';

	protected function getInput()
	{

		// Initialize variables.
		$html 		= array();
		
		$idA		= 'phFileNameModal';
		$onchange 	= (string) $this->element['onchange'];
		$size     = ($v = $this->element['size']) ? ' size="' . $v . '"' : '';
		$class    = ($v = $this->element['class']) ? ' class="' . $v . '"' : 'class="text_area"';
		$required = ($v = $this->element['required']) ? ' required="required"' : '';
		
		
		// Manager
		$manager		=	$this->element['manager'] ? $this->element['manager'] : '';
		$managerOutput	 =	$this->element['manager'] ? '&amp;manager='.(string) $this->element['manager'] : '';
		
		$idA .= 'mo' . $manager;
		$group = PhocaDownloadSettings::getManagerGroup((string) $this->element['manager']);
		$textButton	= 'COM_PHOCADOWNLOAD_FORM_SELECT_'.strtoupper($group['t']);
		
		$link = 'index.php?option=com_phocadownload&amp;view=phocadownloadmanager'.$group['c'].$managerOutput.'&amp;field='.$this->id;

	

		// Load the modal behavior script.
		//JHtml::_('behavior.modal', 'a.modal_'.$this->id);
		
	
		
		// Build the script.
	/*	$script = array();
		$script[] = '	function phocaSelectFileName_'.$this->id.'(title) {';
		$script[] = '		document.getElementById("'.$this->id.'_id").value = title;';
		$script[] = '		'.$onchange;
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

*/
		/*$html[] = '<div class="fltlft">';
		$html[] = '	<input type="text" id="'.$this->id.'_id" name="'.$this->name.'" value="'. $this->value.'"' .
					' '.$attr.' />';
		$html[] = '</div>';

		// Create the user select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '		<a class="modal_'.$this->id.'" title="'.JText::_($textButton).'"' .
							' href="'.($this->element['readonly'] ? '' : $link).'"' .
							' rel="{handler: \'iframe\', size: {x: 780, y: 560}}">';
		$html[] = '			'.JText::_($textButton).'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';*/
		
		
		JHtml::_('jquery.framework');
		
		JFactory::getDocument()->addScriptDeclaration('
			function phocaSelectFileName_' . $this->id . '(name) {
				document.getElementById("' . $this->id . '").value = name;
				jQuery(\'#'.$idA.'\').modal(\'toggle\');
			}
		');
		
		$html[] = '<span class="input-append"><input type="text" ' . $required . ' id="' . $this->id . '" name="' . $this->name . '"'
			. ' value="' . $this->value . '"' . $size . $class . ' />';
		$html[] = '<a href="#'.$idA.'" role="button" class="btn btn-primary" data-toggle="modal" title="' . JText::_($textButton) . '">'
			. '<span class="icon-list icon-white"></span> '
			. JText::_($textButton) . '</a></span>';
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			$idA,
			array(
				'url'    => $link,
				'title'  => JText::_($textButton),
				'width'  => '700px',
				'height' => '400px',
				'modalWidth' => '80',
				'bodyHeight' => '70',
				'footer' => '<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
					. JText::_('COM_PHOCADOWNLOAD_CLOSE') . '</button>'
			)
		);

		return implode("\n", $html);
	}
}