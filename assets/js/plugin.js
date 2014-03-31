(function() {
	var pluginUrl; // Used later for the icon's url

	tinymce.create('tinymce.plugins.tweeterfeed', {
		
		// Initiate the plugin
		init : function(ed, url) {
			ed.addCommand("showPopup", function ( a, param )
			{			
				
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
			
			pluginUrl = url + '/../';
		},
		
		// Create the split button and menu
		createControl: function( btn, menu ) {
			if( btn === 'tweeterfeed' ) {
				
				var a = this;
				
				// Create a split button (a button with a down arrow for the dropdown)
				var btn = menu.createSplitButton('shortcodes_button', {
					title : 'Insert a shortcode',
					image : pluginUrl + 'img/plugin-icon.png'
				});	
				
				// Create the dropdown list according to the config file
				var items = askupa_twitter_feed.menu_items;
				btn.onRenderMenu.add(function(c, ed) {
					jQuery(items).each(function(i) {
						a.addMenuItem( ed, items[i].label, items[i].name, items[i].disabled);
					});
				})
				
				return btn;
				
			}
			return null;
		},
		
		// Add a menu item and execute "showPopup" on click
		addMenuItem: function ( ed, title, id, disabled ) {
			
			ed.add({
				title: title,
				class: disabled ? 'mceMenuItemDisabled' : '',
				onclick: disabled ? 
					function() {alert("This option is not available in the demo version")} :
					function () {
						tinyMCE.activeEditor.execCommand('showPopup', false, {
							id: id,
							title: title
						})
					}
			});
		},
		
		// The info of the button
		getInfo : function() {
			return {
				longname : "Twitter Feed Shortcode",
				author : 'Askupa Software',
				authorurl : 'http://www.askupasoftware.com',
				infourl : 'http://products.askupasoftware.com/twitter-feed/',
				version : "1.4"
			};
		}
	});
	
	// Add the button to the tinyMCE menu
	tinymce.PluginManager.add('tweeterfeed', tinymce.plugins.tweeterfeed);
	
})();