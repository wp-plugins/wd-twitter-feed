jQuery(document).ready(function ($) {
	
	var timer, // Holds the timeOut
		lastResult;
	
	// Initiate 
	init();
	
	// rebind initiation after widget save
	$('div[id*=wdtwitterfeed]').on('ajaxComplete', function() {
		init();
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
				setValidatorTo( data );
				lastResult = data;
			}
		});
	}
	
	function setValidatorTo( obj ) {
		$('.user-validator')
			.val(obj.data)
			.removeClass('user-validator-valid user-validator-invalid')
			.addClass(obj.class);
	}
	
	function init() {
		// Bind the color picker
		$('.wd-color-field').wpColorPicker();
		
		// Set the validator to the last result
		if( lastResult )
			setValidatorTo( lastResult );
	}
});
