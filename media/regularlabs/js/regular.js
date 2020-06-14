/**
 * @package         Regular.js
 * @description     A light and simple JavaScript Library
 *
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://github.com/regularlabs/regularjs
 * @copyright       Copyright © 2019 Regular Labs - All Rights Reserved
 * @license         https://github.com/regularlabs/regularjs/blob/master/LICENCE MIT
 */

"use strict";

if (typeof window.Regular === 'undefined'
	|| typeof Regular.version === 'undefined'
	|| Regular.version < 1.2) {

	window.Regular = new function() {
		/**
		 *
		 * PUBLIC PROPERTIES
		 *
		 */

		this.version = 1.2;

		/**
		 *
		 * PUBLIC METHODS
		 *
		 */

		/**
		 * Sets a global alias for the Regular class.
		 *
		 * @param word  A string (character or word) representing the alias for the Regular class.
		 *
		 * @return boolean
		 */
		this.alias = function(word) {
			if (typeof window[word] !== 'undefined') {
				console.error(`Cannot set '${word}' as am alias of Regular, as it already exists.`);

				return false;
			}

			window[word] = $;

			return true;
		};

		/**
		 * Returns a boolean based on whether the element contains one or more of the given class names.
		 *
		 * @param selector  A CSS selector string or a HTMLElement object.
		 * @param classes   A string or array of class names.
		 * @param matchAll  Optional boolean whether the element should have all given classes (true) or at least one (false).
		 *
		 * @return boolean
		 */
		this.hasClasses = function(selector, classes, matchAll = true) {
			if (!selector) {
				return false;
			}

			const element = typeof selector === 'string'
				? document.querySelectorAll(selector)
				: selector;

			if (typeof classes === 'string') {
				classes = classes.split(' ');
			}

			let hasClass = false;

			for (const clss of classes) {
				hasClass = element.classList.contains(clss);

				if (matchAll && !hasClass) {
					return false;
				}

				if (!matchAll && hasClass) {
					return true;
				}
			}

			return hasClass;
		};

		/**
		 * Adds given class name(s) to the element(s).
		 *
		 * @param selector  A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 * @param classes   A string or array of class names.
		 */
		this.addClasses = function(selector, classes) {
			doClasses('add', selector, classes);
		};

		/**
		 * Removes given class name(s) from the element(s).
		 *
		 * @param selector  A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 * @param classes   A string or array of class names.
		 */
		this.removeClasses = function(selector, classes) {
			doClasses('remove', selector, classes);
		};

		/**
		 * Toggles given class name(s) of the element(s).
		 *
		 * @param selector  A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 * @param classes   A string or array of class names.
		 */
		this.toggleClasses = function(selector, classes) {
			doClasses('toggle', selector, classes);
		};

		/**
		 * Shows the given element(s) (changes opacity and display attributes).
		 *
		 * @param selector  A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 */
		this.show = function(selector) {
			if (!selector) {
				return;
			}

			const element = typeof selector === 'string'
				? document.querySelectorAll(selector)
				: selector;

			if ('forEach' in element) {
				element.forEach(subElement => $.show(subElement));
				return;
			}

			let computedDisplay = getComputedStyle(element, 'display');

			if (!('origDisplay' in element)) {
				element.origDisplay = computedDisplay == 'none'
					? getDefaultComputedStyle(element, 'display')
					: computedDisplay;
			}

			if (computedDisplay == 'none') {
				element.style.display = ('origDisplay' in element) ? element.origDisplay : '';
			}

			computedDisplay = getComputedStyle(element, 'display');
			if (computedDisplay == 'none') {
				element.style.display = 'block';
			}

			element.style.visibility = 'visible';
			element.style.opacity    = 1;
		};

		/**
		 * Hides the given element(s) (changes opacity and display attributes).
		 *
		 * @param selector  A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 */
		this.hide = function(selector) {
			if (!selector) {
				return;
			}

			const element = typeof selector === 'string'
				? document.querySelectorAll(selector)
				: selector;

			if ('forEach' in element) {
				element.forEach(subElement => $.hide(subElement));
				return;
			}

			const computedDisplay = getComputedStyle(element, 'display');

			if (computedDisplay != 'none' && !('origDisplay' in element)) {
				element.origDisplay = computedDisplay;
			}

			element.style.display    = 'none';
			element.style.visibility = 'hidden';
			element.style.opacity    = 0;
		};

		/**
		 * Fades in the the given element(s).
		 *
		 * @param selector    A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 * @param duration    Optional duration of the effect in milliseconds.
		 * @param oncomplete  Optional callback function to execute when effect is completed.
		 */
		this.fadeIn = function(selector, duration = 250, oncomplete) {
			if (!selector) {
				return;
			}

			const element = typeof selector === 'string'
				? document.querySelectorAll(selector)
				: selector;

			if ('forEach' in element) {
				element.forEach(subElement => $.fadeIn(subElement, duration, oncomplete));
				return;
			}

			element.setAttribute('data-fading', 'in');

			const wait        = 50; // amount of time between steps
			const nr_of_steps = duration / wait;
			const change      = 1 / nr_of_steps; // time to wait before next step

			if (!element.style.opacity || element.style.opacity == 1) {
				element.style.opacity = 0;
			}
			if (element.style.display == 'none') {
				element.style.display = 'block';
			}

			(function fade() {
				if (element.getAttribute('data-fading') == 'out') {
					return;
				}
				element.style.opacity = parseFloat(element.style.opacity) + change;
				if (element.style.opacity >= 1) {
					$.show(element);
					element.setAttribute('data-fading', '');
					if (oncomplete) {
						oncomplete.call(element);
					}
					return;
				}
				setTimeout(function() {
					fade.call();
				}, wait);
			})();
		};

		/**
		 * Fades out the the given element(s).
		 *
		 * @param selector    A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 * @param duration    Optional duration of the effect in milliseconds.
		 * @param oncomplete  Optional callback function to execute when effect is completed.
		 */
		this.fadeOut = function(selector, duration = 250, oncomplete) {
			if (!selector) {
				return;
			}

			const element = typeof selector === 'string'
				? document.querySelectorAll(selector)
				: selector;

			if ('forEach' in element) {
				element.forEach(subElement => $.fadeOut(subElement, duration, oncomplete));
				return;
			}

			element.setAttribute('data-fading', 'out');

			const wait        = 50; // amount of time between steps
			const nr_of_steps = duration / wait;
			const change      = 1 / nr_of_steps; // time to wait before next step

			if (!element.style.opacity || element.style.opacity == 0) {
				element.style.opacity = 1;
			}

			(function fade() {
				if (element.getAttribute('data-fading') == 'in') {
					return;
				}
				element.style.opacity = parseFloat(element.style.opacity) - change;
				if (element.style.opacity <= 0) {
					$.hide(element);
					element.setAttribute('data-fading', '');
					if (oncomplete) {
						oncomplete.call(element);
					}
					return;
				}
				setTimeout(function() {
					fade.call();
				}, wait);
			})();
		};

		/**
		 * Runs a function when the document is loaded (on ready state).
		 *
		 * @param func  Callback function to execute when document is ready.
		 */
		this.onReady = function(func) {
			/in/.test(document.readyState)
				? setTimeout(() => {
					Regular.onReady(func);
				}, 9)
				: func.call();
		};

		/**
		 * Converts a string with HTML code to 'DOM' elements.
		 *
		 * @param html  String with HTML code.
		 *
		 * @return element
		 */
		this.createElementFromHTML = function(html) {
			return document.createRange().createContextualFragment(html);
		};

		/**
		 * Loads a url with optional POST data and optionally calls a function on success or fail.
		 *
		 * @param url      String containing the url to load.
		 * @param data     Optional string representing the POST data to send along.
		 * @param success  Optional callback function to execute when the url loads successfully (status 200).
		 * @param fail     Optional callback function to execute when the url fails to load.
		 */
		this.loadUrl = function(url, data, success, fail) {
			const request = new XMLHttpRequest();

			request.open("POST", url, true);

			request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			request.onreadystatechange = function() {
				if (this.readyState != 4) {
					return;
				}

				if (this.status == 200) {
					success && success.call(null, this.responseText, this.status, this);
					return;
				}

				fail && fail.call(null, this.responseText, this.status, this);
			};

			request.send(data);
		};

		/**
		 *
		 * ALIASES
		 *
		 */

		this.as = this.alias;
		this.hasClass    = this.hasClasses;
		this.addClass    = this.addClasses;
		this.removeClass = this.removeClasses;
		this.toggleClass = this.toggleClasses;

		/**
		 *
		 * PRIVATE FUNCTIONS
		 *
		 */

		/**
		 * Executes an action on the element(s) to add/remove/toggle classes.
		 *
		 * @param action    A string that identifies the action: add|remove|toggle.
		 * @param selector  A CSS selector string, a HTMLElement object or a collection of HTMLElement objects.
		 * @param classes   A string or array of class names.
		 */
		const doClasses = function(action, selector, classes) {
			if (!selector) {
				return;
			}

			const element = typeof selector === 'string'
				? document.querySelectorAll(selector)
				: selector;

			if ('forEach' in element) {
				element.forEach(subElement => doClasses(action, subElement, classes));
				return;
			}

			if (typeof classes === 'string') {
				classes = classes.split(' ');
			}

			element.classList[action](...classes);
		};

		/**
		 * Finds the computed style of an element.
		 *
		 * @param element   A HTMLElement object.
		 * @param property  The style property that needs to be returned.
		 *
		 * @returns mixed
		 */
		const getComputedStyle = function(element, property) {
			if (!element) {
				return null;
			}

			return window.getComputedStyle(element).getPropertyValue(property);
		};

		/**
		 * Finds the default computed style of an element by its type.
		 *
		 * @param element   A HTMLElement object.
		 * @param property  The style property that needs to be returned.
		 *
		 * @returns mixed
		 */
		const getDefaultComputedStyle = function(element, property) {
			if (!element) {
				return null;
			}

			const defaultElement = document.createElement(element.nodeName);

			document.body.append(defaultElement);
			let propertyValue = window.getComputedStyle(defaultElement).getPropertyValue(property);
			defaultElement.remove();

			return propertyValue;
		};

		/**
		 *
		 * PRIVATE VARIABLES
		 *
		 */

		/**
		 * @param  $  internal shorthand for the 'this' keyword.
		 */
		const $ = this;
	};
}
