/**
 * package:		Twitter Feed
 * version:		1.3
 * author:		Askupa Software <contact@askupasoftware.com>
 * link:		http://products.askupasoftware.com/twitter-feed/
 * facing:		public
 * depends:		{jquery}
 */

jQuery(document).ready(function ($) {
	
	var popUpWidth = 700;
	var popUpHeight = 345;
	
	// Bind onclick tweet actions
	$('a.web-intent').each(function() {
		$(this).click(function(e) {
			e.preventDefault(); // Prevent the link from being opened
			newwindow = window.open(
				this.getAttribute("href"),
				this.getAttribute("title"),
				'height=' + popUpHeight + ',width=' + popUpWidth
			);
			
			// Focus
			if(window.focus) { newwindow.focus(); }

			// Centralize the popup window
			newwindow.moveTo((screen.width-popUpWidth)/2,(screen.height-popUpHeight)/2);
			/*
			$.get(
				this.href,
				function(response) {
					$('#twitter-action-response').html(response);
				}
			);*/
			return false;
		});
	});
	
	// Hide debug window onclick
	$('#askupa-close-debug-window').click(function() {
		$('#askupa-twitter-feed-debug').hide();
	});
});