/**
 * package:		Twitter Feed
 * version:		1.43
 * author:		Askupa Software <contact@askupasoftware.com>
 * link:		http://products.askupasoftware.com/twitter-feed/
 * facing:		admin
 * depends:		{jquery}
 */

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
	$(document).on( "keyup", 'input[id*=user]', function() {

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
			url: askupa_twitter_feed.plugin_url + '/config/widget/UsernameValidation.php',
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
		// Set the validator to the last result
		if( lastResult )
			setValidatorTo( lastResult );
	}
});
