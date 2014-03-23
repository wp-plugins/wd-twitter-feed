/**
 * package:		Twitter Feed
 * version:		1.3
 * author:		Askupa Software <contact@askupasoftware.com>
 * link:		http://products.askupasoftware.com/twitter-feed/
 * facing:		admin
 * depends:		{jquery}
 */

jQuery(document).ready(function ($) {
	
	var timer, // Holds the timeOut
		lastResult;	init();	$('div[id*=wdtwitterfeed]').on('ajaxComplete', function() {
		init();
	});	$(document).on( "keyup", 'input[id*=user]', function() {		clearTimeout(timer);

		var userInput = this.value,
			defaultValue =  $('.user-validator').prop("defaultValue"),
			delay = 500;		if(userInput == '') {
			$('.user-validator')
				.removeClass('user-validator-valid user-validator-invalid')
				.val( defaultValue );
			
			return; // Bail early
		}		$('.user-validator').val('validating...');    	timer = setTimeout(function() {
    		validateScreenName( userInput );
    	}, delay);
    		
	});	function validateScreenName( name ) {
			
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
	
	function init() {		if( lastResult )
			setValidatorTo( lastResult );
	}
});
