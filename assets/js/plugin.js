(function() {
	
	/* Twitter Feed Plugin class
	/*---------------------------------*/
	var TFplugin = function () {
		this.menu = [];
	};
	
	/* Plugin initiation function
	/*---------------------------------*/
	TFplugin.prototype.init = function() {
		
		// Create a local reference
		var self = this;
		
		// Create the dropdown menu
		this.createMenu();
		
		// Add the button to the TinyMCE Plugin manager
		tinymce.PluginManager.add('askupatwitterfeed', function( editor, url ) {
			
			// Add the button with the dropdown menu
			editor.addButton('askupatwitterfeed', {
				type: 'splitbutton',
				icon: 'twitter-feed-icon', // The style for this is located under editor.css
				onclick: function() {},
				menu: self.menu,
				tooltip: 'Twitter Feed Shortcode'
			});
			
			// Add the command to show the popup
			editor.addCommand("showPopup", function( a, param ) {				
				var title = 'Insert a new ' + param.title + ' shortcode';
				var data = {
					action: 'askupatwitterfeed_thickbox_callback',
					item: param.id,
					width: 640,
					height: '90%'
				};
				var callback = ajaxurl + "?" + jQuery.param(data);

				// load thickbox
				tb_show( title, callback );

				// Trigger the popup funcions in popup.js
				jQuery(document).trigger('tb_show');

			});
		});
	};
	
	/* Create the drop down menu
	/*---------------------------------*/
	TFplugin.prototype.createMenu = function() {
		
		// Create the dropdown list according to the config file
		var items = askupa_twitter_feed.menu_items;
		var self = this;
		
		jQuery(items).each(function(i) {
			self.addMenuItem( items[i].label, items[i].name, items[i].disabled );
		});
	};
	
	/* Add menu item to the dropdown menu
	/*---------------------------------*/
	TFplugin.prototype.addMenuItem = function( title, id, disabled ) {
		this.menu.push({
			text: title,
			disabled: disabled ? true : false,
			onclick: disabled ? 
				function() {alert("This option is not available in the demo version"); } :
				function () {
					tinyMCE.activeEditor.execCommand('showPopup', false, {
						id: id,
						title: title
					});
				}
		});
	};
	
	/* The info of the button
	/*---------------------------------*/
	TFplugin.prototype.getInfo = function() {
		return {
			longname:	'Twitter Feed Shortcode',
			author:		'Askupa Software',
			authorurl:	'http://www.askupasoftware.com',
			infourl:	'http://products.askupasoftware.com/twitter-feed/',
			version:	'1.43'
		};
	};
	
	// Let's get this party started!
	var plugin = new TFplugin();
	plugin.init();
	
})();