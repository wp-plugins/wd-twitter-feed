/**
 * package:		Twitter Feed
 * version:		1.2
 * author:		Askupa Software <contact@askupasoftware.com>
 * link:		http://products.askupasoftware.com/twitter-feed/
 * facing:		custom
 * depends:		{jquery,wp-color-picker}
 */

jQuery(document).ready(function ($) {
	var widget = new WidgetFramework();
	widget.init();
});

var $ = jQuery;

/**
 * Widget Framework Class
 */
function WidgetFramework() {}

/**
 * 
 * @returns {undefined}
 */
WidgetFramework.prototype.init = function() {
	// Bind the color picker
	$('.wd-color-field').wpColorPicker();
};