/**
 * @package         Regular Labs Library
 * @version         20.3.22179
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

"use strict";

if (typeof window.RegularLabsForm === 'undefined'
	|| typeof RegularLabsForm.version === 'undefined'
	|| RegularLabsForm.version < '20.3.22179') {

	(function($) {
		window.RegularLabsForm = {
			version: '20.3.22179',

			getValue: function(name, escape) {
				let $field = $(`[name="${name}"]`);

				if (!$field.length) {
					$field = $(`[name="${name}[]"]`);
				}

				if (!$field.length) {
					return;
				}

				const type = $field[0].type;

				switch (type) {
					case 'radio':
						$field = $(`[name="${name}"]:checked`);
						break;

					case 'checkbox':
						return this.getValuesFromList($(`[name="${name}[]"]:checked`), escape);

					case 'select':
					case 'select-one':
					case 'select-multiple':
						return this.getValuesFromList($field.find('option:checked'), escape);
				}

				return this.prepareValue($field.val(), escape);
			},

			getValuesFromList: function($elements, escape) {
				const self = this;

				const values = [];

				$elements.each(function() {
					values.push(self.prepareValue($(this).val(), escape));
				});

				return values;
			},

			prepareValue: function(value, escape) {
				if (!isNaN(value) && value.indexOf('.') < 0) {
					return parseInt(value);
				}

				if (escape) {
					value = value.replace(/"/g, '\\"');
				}

				return value.trim();
			},

			toTextValue: function(str) {
				return str.toString().replace(/^[\s-]*/, '').trim();
			},

			toSimpleValue: function(str) {
				return str.toString().toLowerCase().replace(/[^0-9a-z]/g, '').trim();
			},

			// preg_quote: function(str) {
			// 	return str.toString().replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!<>\|\:])/g, '\\$1');
			// },

			// escape: function(str) {
			// 	return str.toString().replace(/([\"])/g, '\\$1');
			// },

			setRadio: function(id, value) {
				value = value ? 1 : 0;

				const selector = `input#jform_${id}${value},input#jform_params_${id}${value},input#advancedparams_${id}${value}`;

				document.getElements(selector).each(function(el) {
					el.click();
				});
			},

			initCheckAlls: function(id, classname) {
				$(`#${id}`).attr('checked', this.allChecked(classname));
				$(`input.${classname}`).click(function() {
					$(`#${id}`).attr('checked', this.allChecked(classname));
				});
			},

			allChecked: function(classname) {
				return $(`input.${classname}:checkbox:not(:checked)`).length < 1;
			},

			checkAll: function(checkbox, classname) {
				const allchecked = this.allChecked(classname);
				$(checkbox).attr('checked', !allchecked);
				$(`input.${classname}`).attr('checked', !allchecked);
			},

			// getEditorSelection: function(editorID) {
			// 	const editorTextarea = document.getElementById(editorID);
			//
			// 	if (!editorTextarea) {
			// 		return '';
			// 	}
			//
			// 	const editorFrame = editorTextarea.parentNode.querySelector('iframe');
			//
			// 	if (!editorFrame) {
			// 		return '';
			// 	}
			//
			// 	const contentWindow = editorFrame.contentWindow;
			//
			// 	if (typeof contentWindow.getSelection !== 'undefined') {
			// 		const sel = contentWindow.getSelection();
			//
			// 		if (sel.rangeCount) {
			// 			const container = contentWindow.document.createElement("div");
			// 			const len       = sel.rangeCount;
			// 			for (let i = 0; i < len; ++i) {
			// 				container.appendChild(sel.getRangeAt(i).cloneContents());
			// 			}
			//
			// 			return container.innerHTML;
			// 		}
			//
			// 		return '';
			// 	}
			//
			// 	if (typeof contentWindow.document.selection !== 'undefined') {
			// 		if (contentWindow.document.selection.type == "Text") {
			// 			return contentWindow.document.selection.createRange().htmlText;
			// 		}
			// 	}
			//
			// 	return '';
			// },

			toggleSelectListSelection: function(id) {
				const el = document.getElement(`#${id}`);
				if (el && el.options) {
					for (let i = 0; i < el.options.length; i++) {
						if (!el.options[i].disabled) {
							el.options[i].selected = !el.options[i].selected;
						}
					}
				}
			},

			prependTextarea: function(id, content, separator) {
				const textarea      = $(`#${id}`);
				let originalContent = textarea.val().trim();

				if (originalContent && separator) {
					separator       = separator == 'none' ? '' : `\n\n${separator}`;
					originalContent = `${separator}\n\n${originalContent}`;
				}

				textarea.val(`${content}${originalContent}`);
				this.moveCursorInTextareaTo(id, content.length);
			},

			moveCursorInTextareaTo: function(id, position) {
				const textarea = document.getElementById(id);

				if (textarea.setSelectionRange) {
					textarea.focus();
					textarea.setSelectionRange(position, position);
					textarea.scrollTop = 0;
					return;
				}

				if (textarea.createTextRange) {
					var range = textarea.createTextRange();
					range.moveStart('character', position);
					range.select();
					textarea.scrollTop = 0;
				}
			},

			setToggleTitleClass: function(input, value) {
				const el = $(input).parent().parent().parent().parent();

				el.removeClass('alert-success').removeClass('alert-error');
				if (value === 2) {
					el.addClass('alert-error');
				} else if (value) {
					el.addClass('alert-success');
				}
			}
		};

		$(document).ready(function() {
			removeEmptyControlGroups();
			addShowOnTriggers();

			function removeEmptyControlGroups() {
				// remove all empty control groups
				$('div.control-group > div').each(function(i, el) {
					if (
						$(el).html().trim() == ''
						&& (
							$(el).attr('class') == 'control-label'
							|| $(el).attr('class') == 'controls'
						)
					) {
						$(el).remove();
					}
				});
				$('div.control-group').each(function(i, el) {
					if ($(el).html().trim() == '') {
						$(el).remove();
					}
				});
				$('div.control-group > div.hide').each(function(i, el) {
					$(el).parent().css('margin', 0);
				});
			}

			/**
			 * Adds keyup triggers to fields to trigger show/hide of showon fields
			 */
			function addShowOnTriggers() {
				const fieldIDs = [];

				$('[data-showon]').each(function() {
					const $target  = $(this);
					const jsonData = $target.data('showon') || [];

					// Collect an all referenced elements
					for (let i = 0, len = jsonData.length; i < len; i++) {
						fieldIDs.push(`[name="${jsonData[i]['field']}"]`);
						fieldIDs.push(`[name="${jsonData[i]['field']}[]"]`);
					}
				});

				// Trigger the change event on keyup
				$(fieldIDs.join(',')).on('input', function() {
					$(this).change();
				});
			}
		});
	})(jQuery);
}
