jQuery(document).ready(function ($) {
	// rebind effect after widget save
	$('#widgets-right').ajaxComplete(function() {
		// WP Color Picker
		$('.wd-color-field').wpColorPicker();
	});
});
