jQuery(document).ready(function ($) {
	
	var timer; // Holds the timeOut
	
	// rebind color-picker after widget save
	$('#widgets-right').livequery(function() {
		$('.wd-color-field').wpColorPicker();
	}).ajaxComplete(function() {
		$('.wd-color-field').wpColorPicker();
	});
	
	// User validation
	$(document).on( "keyup", '#twitter-username', function() {

		// Clear the previous timeout
		clearTimeout(timer);

		var userInput = this.value,
			defaultValue =  $('.user-validator').prop("defaultValue"),
			delay = 500;
		
		// No user input
		if(userInput == '') {
			$('.user-validator')
				.removeClass('user-validator-valid user-validator-invalid')
				.val( defaultValue );
			
			return; // Bail early
		}
		
		// Show this while making the ajax request
		$('.user-validator').val('validating...');

		// Validate the user name only after the user has 
		// stopped typing for the specified amount of time
    	timer = setTimeout(function() {
    		validateScreenName( userInput );
    	}, delay);
    		
	});
	
	// Make the ajax request to validate the screen name
	function validateScreenName( name ) {
			
		$.ajax({
			dataType: "json",
			url: wdtf_options.url,
			data: { screen_name: name },
			success: function(data) {
				$('.user-validator')
					.val(data.data)
					.removeClass('user-validator-valid user-validator-invalid')
					.addClass(data.class);
			}
		});
	}
});
