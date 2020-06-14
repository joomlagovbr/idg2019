<?php
/**
 * @package         Regular Labs Library
 * @version         20.3.22179
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;
use RegularLabs\Library\Document as RL_Document;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_Ajax extends \RegularLabs\Library\Field
{
	public $type = 'Ajax';

	protected function getInput()
	{
		RL_Document::loadMainDependencies();

		$class = $this->get('class', 'btn');

		if ($this->get('disabled'))
		{
			return $this->getButton($class . ' disabled', 'disabled');
		}

		$loading = 'jQuery("#' . $this->id . ' span:nth-child(1)").attr("class", "icon-refresh icon-spin");';

		$success = '
			jQuery("#' . $this->id . '").removeClass("btn-warning").addClass("btn-success");
			jQuery("#' . $this->id . ' span:nth-child(1)").attr("class", "icon-ok");
			if(data){
				jQuery("#message_' . $this->id . '").addClass("alert alert-success alert-noclose alert-inline").html(data);
			}
			';

		$error = '
			jQuery("#' . $this->id . '").removeClass("btn-success").addClass("btn-warning");
			jQuery("#' . $this->id . ' span:nth-child(1)").attr("class", "icon-warning");
			if(data){
				let error = data;
				if(data.statusText) { 
					error = data.statusText;
					if(data.responseText.test(/<blockquote>/)) {
						error = data.responseText.replace(/^[.\\\\s\\\\S]*?<blockquote>([.\\\\s\\\\S]*?)<\\\\/blockquote>[.\\\\s\\\\S]*$/gm, "$1");
					}
				}
				jQuery("#message_' . $this->id . '").addClass("alert alert-danger alert-noclose alert-inline").html(error);
			}';

		if ($this->get('success-disabled'))
		{
			$success .= '
			jQuery("#' . $this->id . '").disabled = true;
			jQuery("#' . $this->id . '").addClass("disabled");
			jQuery("#' . $this->id . '").attr("onclick", "return false;");
			';
		}

		if ($this->get('success-text') || $this->get('error-text'))
		{
			$success_text = $this->get('success-text', $this->get('text'));
			$error_text   = $this->get('error-text', $this->get('text'));

			$success .= '
			jQuery("#' . $this->id . ' span:nth-child(2)").text("' . addslashes(JText::_($success_text)) . '");
			';

			$error .= '
			jQuery("#' . $this->id . ' span:nth-child(2)").text("' . addslashes(JText::_($error_text)) . '");
			';
		}

		$query = '';

		if ($url_query = $this->get('url-query'))
		{
			$name_prefix = $this->form->getFormControl() . '\\\[' . $this->group . '\\\]';
			$id_prefix   = $this->form->getFormControl() . '_' . $this->group . '_';
			$query_parts = [];
			$url_query   = explode(',', $url_query);

			foreach ($url_query as $url_query_part)
			{
				list($key, $id) = explode(':', $url_query_part);

				$el_name = 'document.querySelector("input[name=' . $name_prefix . '\\\[' . $id . '\\\]]:checked")';
				$el_id   = 'document.querySelector("#' . $id_prefix . $id . '")';

				$query_parts[] = '`&' . $key . '=`'
					. ' + encodeURI(' . $el_name . ' ? ' . $el_name . '.value : (' . $el_id . ' ? ' . $el_id . '.value' . ' : ""))';
			}

			$query = '+' . implode('+', $query_parts);
		}

		$script = 'function loadAjax' . $this->id . '() {
				' . $loading . '
				jQuery("#message_' . $this->id . '").attr("class", "").html("");
				RegularLabsScripts.loadajax(
					`' . addslashes($this->get('url')) . '`' . $query . ',
					`
					if(data == "" || data.substring(0,1) == "+") {
						data = data.trim().replace(/^[+]/, "");
						' . $success . '
					} else {
						data = data.trim().replace(/^[-]/, "");
						' . $error . '
					}`,
					`' . $error . '`
				);
			}';

		$script = preg_replace('#\s*\n\s*#', ' ', $script);

		JFactory::getDocument()->addScriptDeclaration($script);

		$attributes = 'onclick="loadAjax' . $this->id . '();return false;"';

		return $this->getButton($class, $attributes);
	}

	private function getButton($class = 'btn', $attributes = '')
	{
		$icon = $this->get('icon', '')
			? 'icon-' . $this->get('icon', '')
			: '';

		return
			'<button id="' . $this->id . '" class="' . $class . '"'
			. ' title="' . JText::_($this->get('description')) . '"'
			. ' ' . $attributes . '>'
			. '<span class="' . $icon . '"></span> '
			. '<span>' . JText::_($this->get('text', $this->get('label'))) . '</span>'
			. '</button>'
			. '<div id="message_' . $this->id . '"></div>';
	}
}
