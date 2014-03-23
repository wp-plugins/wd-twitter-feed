(function() {
	var pluginUrl; // Used later for the icon's url

	tinymce.create('tinymce.plugins.tweeterfeed', {		init : function(ed, url) {
			ed.addCommand("showPopup", function ( a, param )
			{			
				
				var title = 'Insert a new ' + param.title + ' shortcode';
				var data = {
					action: 'askupatwitterfeed_thickbox_callback',
					item: param.id,
					width: 640,
					height: '90%'
				};
				var callback = ajaxurl + "?" + jQuery.param(data);				tb_show( title, callback );				jQuery(document).trigger('tb_show');
				
			});
			
			pluginUrl = url + '/../';
		},		createControl: function( btn, menu ) {
			if( btn === 'tweeterfeed' ) {
				
				var a = this;				var btn = menu.createSplitButton('shortcodes_button', {
					title : 'Insert a shortcode',
					image : pluginUrl + 'img/plugin-icon.png'
				});				var items;
				jQuery.get(
					ajaxurl, 
					{action: 'askupatwitterfeed_menu_items'}, 
					function(response) {
						
						items = jQuery.parseJSON(response);						btn.onRenderMenu.add(function(c, ed) {
							jQuery(items).each(function(i) {
								a.addMenuItem( ed, items[i].label, items[i].name);
							});
						})
				});
				
				return btn;
				
			}
			return null;
		},		addMenuItem: function ( ed, title, id ) {
			
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand('showPopup', false, {
						id: id,
						title: title
					});
				}
			});
		},		getInfo : function() {
			return {
				longname : "Twitter Feed Shortcode",
				author : 'Askupa Software',
				authorurl : 'http://www.askupasoftware.com',
				infourl : 'http://products.askupasoftware.com/twitter-feed/',
				version : "1.3"
			};
		}
	});	tinymce.PluginManager.add('tweeterfeed', tinymce.plugins.tweeterfeed);
	
})();