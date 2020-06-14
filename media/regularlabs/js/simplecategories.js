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

(function($) {
	$(document).ready(function() {
		// remove all empty control groups
		$('div.rl_simplecategory').each(function(i, el) {
			var $el = $(el);

			var func = function() {
				var new_value = $(this).val();

				if (new_value == '-1') {
					$el.find('.rl_simplecategory_value').val($el.find('.rl_simplecategory_new input').val());
					return;
				}

				$el.find('.rl_simplecategory_value').val(new_value);
			};

			$el.find('.rl_simplecategory_select select').on('change', func).on('keyup', func);
			$el.find('.rl_simplecategory_new input').on('change', func).on('keyup', func);
		});
	});
})(jQuery);
