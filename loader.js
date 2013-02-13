jQuery(document).ready(function ($) {
	// Get widget options
	var options = wdtf_loader_options;
	
	// Iframe style and attributes
	$('iframe#wdtf-iframe').attr({ 
		src : options.src ,
		frameborder : "0" ,
		allowtransparency : "true" ,
		height : (options.height ? options.height : options.minHeight)
	}).css({
		"border" : "none" ,
		"max-width" : "100%" ,
		"min-width" : options.minWidth + "px"
	});
	
	// Hide loader and show the iframe once it has loaded
	$('iframe#wdtf-iframe').load(function() {
		$('div#wdtf-ajax-loader').css('display' , 'none');
		$(this).css('display' , 'block');
	});
});
