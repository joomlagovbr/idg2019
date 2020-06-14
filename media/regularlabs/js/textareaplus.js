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
		$('.rl_resize_textarea').click(function() {
			var $el    = $(this);
			var $field = $(`#${$el.attr('data-id')}`);

			if ($el.hasClass('rl_minimize')) {
				$el.removeClass('rl_minimize').addClass('rl_maximize');
				$field.css({'height': $el.attr('data-min')});
				return;
			}

			$el.removeClass('rl_maximize').addClass('rl_minimize');
			$field.css({'height': $el.attr('data-max')});
		});
	});
})(jQuery);
