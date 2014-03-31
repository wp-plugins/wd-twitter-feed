/**
 * package:		Twitter Feed
 * version:		1.2
 * author:		Askupa Software <contact@askupasoftware.com>
 * link:		http://products.askupasoftware.com/twitter-feed/
 * facing:		admin
 * depends:		{jquery}
 */

jQuery(document).ready(function($) {
	var popup_funcs = {
    	
    	formId: 'twitter-shortcode-editor',
		shortcode: '',
    	
    	load: function() {
	
			// Bind the submit function
			$('#' + popup_funcs.formId ).submit(function(){
				popup_funcs.update_object();				
				popup_funcs.insert_action();
				return false;
			});
		},
    
    
    /*-------------------------------------------------------------------------------*/
    /* Shortcode object updater function
    /*-------------------------------------------------------------------------------*/
    
		update_object: function() {
		
			var shortcode_template = $('#shortcode').text(),
				regex = new RegExp(/\{(.*?)\}/g),
				inputs = shortcode_template.match(regex),
				obj = shortcode_template;
			
			// Get input values
			$.each( inputs, function( key, value ) {
			
				var input_name = value.replace(/[{}]/g,''),
					input = $('[name="' + input_name + '"]', '#' + popup_funcs.formId),
					val;
				
				// Checkbox
				if(input.is(':checkbox'))
					val = input.prop('checked');
				
				// Other input types
				else
					val = input.val();
				
				obj = obj.replace(value, val);
				
			});
			
			// Update the shortcode
			popup_funcs.shortcode = obj;
		},
    
    
    /*-------------------------------------------------------------------------------*/
    /* Called when the form is submitted
    /*-------------------------------------------------------------------------------*/
    
		insert_action: function() {
			
			// Insert the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, popup_funcs.shortcode );
	
			// closes Thickbox
			tb_remove();
		}
	}
	
	// Run
	$(this).bind('tb_show', function() { 
		// Bind the load function only once when the ajax call is completed
		$('#TB_ajaxContent').one("ajaxComplete", function() { popup_funcs.load(); } );
	 });
});	
