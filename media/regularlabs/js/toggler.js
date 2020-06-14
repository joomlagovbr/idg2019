/**
 * @package         Regular Labs Library
 * @version         20.3.22179
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * @deprecated !!!
 */

"use strict";

if (typeof window.RegularLabsToggler === 'undefined'
	|| typeof RegularLabsToggler.version === 'undefined'
	|| RegularLabsToggler.version < '20.3.22179') {

	(function($) {
		$(document).ready(function() {
			if (!$('.rl_toggler').length) {
				// Try again 2 seconds later, because IE sometimes can't see object immediately
				$(function() {
					if ($('.rl_toggler').length) {
						RegularLabsToggler.initialize();
					}
				}).delay(2000);

				return;
			}

			RegularLabsToggler.initialize();
		});

		window.RegularLabsToggler = {
			version: '20.3.22179',

			togglers: {}, // holds all the toggle areas
			elements: {}, // holds all the elements and their values that affect toggle areas

			initialize: function() {
				this.togglers = $('.rl_toggler');

				if (!this.togglers.length) {
					return;
				}

				this.initTogglers();
			},

			initTogglers: function() {
				const self = this;

				let newTogglers = {};
				this.elements   = {};

				$.each(this.togglers, function(i, toggler) {
					// init togglers
					if (!toggler.id) {
						return;
					}

					$(toggler).show();
					$(toggler).removeAttr('height');

					toggler.height   = $(toggler).height();
					toggler.elements = {};
					toggler.nofx     = $(toggler).hasClass('rl_toggler_nofx');
					toggler.method   = ($(toggler).hasClass('rl_toggler_and')) ? 'and' : 'or';
					toggler.ids      = toggler.id.split('___');

					for (let i = 1; i < toggler.ids.length; i++) {
						const keyval = toggler.ids[i].split('.');

						const key = keyval[0];
						let val   = 1;

						if (keyval.length > 1) {
							val = keyval[1];
						}

						if (typeof toggler.elements[key] === 'undefined') {
							toggler.elements[key] = [];
						}
						toggler.elements[key].push(val);

						if (typeof self.elements[key] === 'undefined') {
							self.elements[key]          = {};
							self.elements[key].elements = [];
							self.elements[key].values   = [];
							self.elements[key].togglers = [];
						}
						self.elements[key].togglers.push(toggler.id);
					}

					newTogglers[toggler.id] = toggler;
				});

				this.togglers = newTogglers;
				newTogglers   = null;

				this.setElements();

				// hide togglers that should be
				$.each(this.togglers, function(i, toggler) {
					self.toggleByID(toggler.id, 1);
				});

				$(document.body).delay(250).css('cursor', '');
			},

			autoHeightDivs: function() {
				// set all divs in the form to auto height
				$.each($('div.col div, div.fltrt div'), function(i, el) {
					if (el.getStyle('height') != '0px'
						&& !el.hasClass('input')
						&& !el.hasClass('rl_hr')
						// GK elements
						&& el.id.indexOf('gk_') < 0
						&& el.className.indexOf('gk_') < 0
						&& el.className.indexOf('switcher-') < 0
					) {
						el.css('height', 'auto');
					}
				});
			},

			toggle: function(name) {
				this.setValues(name);
				for (let i = 0; i < this.elements[name].togglers.length; i++) {
					this.toggleByID(this.elements[name].togglers[i]);
				}
				//this.autoHeightDivs();
			},

			toggleByID: function(id, nofx) {
				if (typeof this.togglers[id] === 'undefined') {
					return;
				}

				const toggler = this.togglers[id];

				const show = this.isShow(toggler);

				if (nofx || toggler.nofx) {
					if (show) {
						$(toggler).show();
					} else {
						$(toggler).hide();
					}
				} else {
					if (show) {
						$(toggler).slideDown();
					} else {
						$(toggler).slideUp();
					}
				}
			},

			isShow: function(toggler) {
				let show = (toggler.method == 'and');

				for (let name in toggler.elements) {
					const vals   = toggler.elements[name];
					const values = this.elements[name].values;

					if (
						values != null && values.length
						&& (
							(vals == '*' && values != '')
							|| (vals.toString().substr(0, 1) === '!' && !RegularLabsScripts.in_array(vals.toString().substr(1), values))
							|| RegularLabsScripts.in_array(vals, values)
						)
					) {
						if (toggler.method == 'or') {
							show = 1;
							break;
						}
					} else {
						if (toggler.method == 'and') {
							show = 0;
							break;
						}
					}
				}

				return show;
			},

			setValues: function(name) {
				const els = this.elements[name].elements;

				const values = [];
				// get value
				$.each(els, function(i, el) {
					switch (el.type) {
						case 'radio':
						case 'checkbox':
							if (el.checked) {
								values.push(el.value);
							}
							break;
						default:
							if (typeof el.elements !== 'undefined' && el.elements.length > 1) {
								for (let i = 0; i < el.elements.length; i++) {
									if (el.checked) {
										values.push(el.value);
									}
								}
							} else {
								values.push(el.value);
							}
							break;
					}
				});
				this.elements[name].values = values;
			},

			setElements: function() {
				const self = this;
				$.each($('input, select, textarea'), function(i, el) {
					const name = el.name
						.replace('@', '_')
						.replace('[]', '')
						.replace(/^(?:jform\[(?:field)?params\]|jform|params|fieldparams|advancedparams)\[(.*?)\]/g, '\$1')
						.replace(/^(.*?)\[(.*?)\]/g, '\$1_\$2')
						.trim();

					if (name !== '') {
						if (typeof self.elements[name] !== 'undefined') {
							self.elements[name].elements.push(el);
							self.setValues(name);
							self.setElementEvents(el, name);
						}
					}
				});
			},

			setElementEvents: function(el, name) {
				if ($(el).attr('togglerEventAdded')) {
					return;
				}

				const self = this;
				let type;

				if (typeof el.type === 'undefined') {
					if ($(el).prop("tagName").toLowerCase() == 'select') {
						type = 'select';
					}
				} else {
					type = el.type;
				}

				const func = function() {
					self.toggle(name);
				};

				$(el).on('input', func);

				if (typeof jQuery !== 'undefined' && type == 'select' || field.type == 'select-one') {
					$(el).on('change', func);
				}

				$(el).attr('togglerEventAdded', 1);
			}
		};
	})(jQuery);
}
