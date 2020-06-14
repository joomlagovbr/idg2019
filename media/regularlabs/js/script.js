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

if (typeof window.RegularLabsScripts === 'undefined'
	|| typeof RegularLabsScripts.version === 'undefined'
	|| RegularLabsScripts.version < '20.3.22179') {

	(function($) {
		window.RegularLabsScripts = {
			version: '20.3.22179',

			ajax_list        : [],
			started_ajax_list: false,
			ajax_list_timer  : null,

			loadajax: function(url, success, fail, query, timeout, dataType, cache) {
				if (url.indexOf('index.php') !== 0 && url.indexOf('administrator/index.php') !== 0) {
					url = url.replace('http://', '');
					url = `index.php?rl_qp=1&url=${encodeURIComponent(url)}`;
					if (timeout) {
						url += `&timeout=${timeout}`;
					}
					if (cache) {
						url += `&cache=${cache}`;
					}
				}

				let base = window.location.pathname;

				base = base.substring(0, base.lastIndexOf('/'));

				if (
					typeof Joomla !== 'undefined'
					&& typeof Joomla.getOptions !== 'undefined'
					&& Joomla.getOptions('system.paths')
				) {
					base = Joomla.getOptions('system.paths').base;
				}

				// console.log(url);
				// console.log(`${base}/${url}`);

				$.ajax({
					type    : 'post',
					url     : `${base}/${url}`,
					dataType: dataType ? dataType : '',
					success : function(data) {
						if (success) {
							success = `data = data ? data : ''; ${success};`.replace(/;\s*;/g, ';');
							eval(success);
						}
					},
					error   : function(data) {
						if (fail) {
							fail = `data = data ? data : ''; ${fail};`.replace(/;\s*;/g, ';');
							eval(fail);
						}
					}
				});
			},

			displayVersion: function(data, extension, version) {
				if (!data) {
					return;
				}

				const xml = this.getObjectFromXML(data);

				if (!xml) {
					return;
				}

				if (typeof xml[extension] === 'undefined') {
					return;
				}

				const dat = xml[extension];

				if (!dat || typeof dat.version === 'undefined' || !dat.version) {
					return;
				}

				const new_version = dat.version;
				const compare     = this.compareVersions(version, new_version);

				if (compare != '<') {
					return;
				}

				let el = $(`#regularlabs_newversionnumber_${extension}`);

				if (el) {
					el.text(new_version);
				}

				el = $(`#regularlabs_version_${extension}`);

				if (el) {
					el.css('display', 'block');
					el.parent().removeClass('hide');
				}
			},

			addToLoadAjaxList: function(url, success, error) {
				// wrap inside the loadajax function (and escape string values)
				url     = url.replace(/'/g, "\\'");
				success = success.replace(/'/g, "\\'");
				error   = error.replace(/'/g, "\\'");

				const action = `RegularLabsScripts.loadajax(
					'${url}',
					'${success};RegularLabsScripts.ajaxRun();',
					'${error};RegularLabsScripts.ajaxRun();'
				)`;

				this.addToAjaxList(action);
			},

			addToAjaxList: function(action) {
				this.ajax_list.push(action);

				if (!this.started_ajax_list) {
					this.ajaxRun();
				}
			},

			ajaxRun: function() {
				if (typeof RegularLabsToggler !== 'undefined') {
					RegularLabsToggler.initialize();
				}

				if (!this.ajax_list.length) {
					return;
				}

				clearTimeout(this.ajax_list_timer);

				this.started_ajax_list = true;

				const action = this.ajax_list.shift();

				eval(`${action};`);

				if (!this.ajax_list.length) {
					return;
				}

				// Re-trigger this ajaxRun function just in case it hangs somewhere
				this.ajax_list_timer = setTimeout(
					function() {
						RegularLabsScripts.ajaxRun();
					},
					5000
				);
			},

			in_array: function(needle, haystack, casesensitive) {
				if ({}.toString.call(needle).slice(8, -1) !== 'Array') {
					needle = [needle];
				}
				if ({}.toString.call(haystack).slice(8, -1) !== 'Array') {
					haystack = [haystack];
				}

				for (let h = 0; h < haystack.length; h++) {
					for (let n = 0; n < needle.length; n++) {
						if (casesensitive) {
							if (haystack[h] == needle[n]) {
								return true;
							}

							continue;
						}

						if (haystack[h].toLowerCase() == needle[n].toLowerCase()) {
							return true;
						}
					}
				}
				return false;
			},

			getObjectFromXML: function(xml) {
				if (!xml) {
					return;
				}

				const obj = [];
				$(xml).find('extension').each(function() {
					const el = [];
					$(this).children().each(function() {
						el[this.nodeName.toLowerCase()] = String($(this).text()).trim();
					});
					if (typeof el.alias !== 'undefined') {
						obj[el.alias] = el;
					}
					if (typeof el.extname !== 'undefined' && el.extname != el.alias) {
						obj[el.extname] = el;
					}
				});

				return obj;
			},

			compareVersions: function(number1, neumber2) {
				number1  = number1.split('.');
				neumber2 = neumber2.split('.');

				let letter1 = '';
				let letter2 = '';

				const max = Math.max(number1.length, neumber2.length);
				for (let i = 0; i < max; i++) {
					if (typeof number1[i] === 'undefined') {
						number1[i] = '0';
					}
					if (typeof neumber2[i] === 'undefined') {
						neumber2[i] = '0';
					}

					letter1     = number1[i].replace(/^[0-9]*(.*)/, '$1');
					number1[i]  = parseInt(number1[i]);
					letter2     = neumber2[i].replace(/^[0-9]*(.*)/, '$1');
					neumber2[i] = parseInt(neumber2[i]);

					if (number1[i] < neumber2[i]) {
						return '<';
					}

					if (number1[i] > neumber2[i]) {
						return '>';
					}
				}

				// numbers are same, so compare trailing letters
				if (letter2 && (!letter1 || letter1 > letter2)) {
					return '>';
				}

				if (letter1 && (!letter2 || letter1 < letter2)) {
					return '<';
				}

				return '=';
			},

			getEditorSelection: function(editorID) {
				const editor_textarea = document.getElementById(editorID);

				if (!editor_textarea) {
					return '';
				}

				const editorIFrame = editor_textarea.parentNode.querySelector('iframe');

				if (!editorIFrame) {
					return '';
				}

				const contentWindow = editorIFrame.contentWindow;

				if (typeof contentWindow.getSelection !== 'undefined') {
					const sel = contentWindow.getSelection();

					if (sel.rangeCount) {
						const container = contentWindow.document.createElement('div');
						const len       = sel.rangeCount;

						for (let i = 0; i < len; ++i) {
							container.appendChild(sel.getRangeAt(i).cloneContents());
						}

						return container.innerHTML;
					}

					return '';
				}

				if (typeof contentWindow.document.selection !== 'undefined'
					&& contentWindow.document.selection.type === 'Text') {
					return contentWindow.document.selection.createRange().htmlText;
				}

				return '';
			},

			/* 2018-11-01: These methods have moved to RegularLabsForm. Keeping them here for backwards compatibility. */
			setRadio                 : function(id, value) {
			},
			initCheckAlls            : function(id, classname) {
			},
			allChecked               : function(classname) {
				return false;
			},
			checkAll                 : function(checkbox, classname) {
			},
			toggleSelectListSelection: function(id) {
			},
			prependTextarea          : function(id, content, separator) {
			},
			setToggleTitleClass      : function(input, value) {
			}
		};
	})(jQuery);
}
