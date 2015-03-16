(function($) {
    
    var config = {
        slug:       'twitterfeed_button',
        title:      'Add a Twitter Feed Widget',
        icon:       'fa fa-twitter',
        text:       null,
        width:      600,
        height:     450,
        max_cols:   3,
        popups:     [
            {
                img:        'static-icon.gif',
                label:      'Static Tweets',
                title:      'Insert Static Tweets',
                template:   '[statictweets skin="<% skin %>" resource="<% resource %>" user="<% user %>" list="<% list %>" query="<% query %>" count="<% count %>" retweets="<% retweets %>" replies="<% replies %>" show="<% show %>"/]'
            },
            {
                img:        'scrolling-icon.gif',
                label:      'Scrolling Tweets',
                title:      'Insert Scrolling Tweets',
                template:   '[scrollingtweets scroll_time="<% scroll_time %>" skin="<% skin %>" resource="<% resource %>" user="<% user %>" list="<% list %>" query="<% query %>" count="<% count %>" retweets="<% retweets %>" replies="<% replies %>"/]',
                disabled:   isFullVersion( false, true )
            },
            {
                img:        'sliding-icon.gif',
                label:      'Sliding Tweets',
                title:      'Insert Sliding Tweets',
                template:   '[slidingtweets slide_dir="<% slide_dir %>" slide_duration="<% slide_duration %>" skin="<% skin %>" resource="<% resource %>" user="<% user %>" list="<% list %>" query="<% query %>" count="<% count %>" retweets="<% retweets %>" replies="<% replies %>" show="<% show %>"/]',
                disabled:   isFullVersion( false, true )
            }
        ]
    };
    
    // Add the button to the editor
    tinymce.PluginManager.add( config.slug, function( editor, url ) {

        var dimensions = calcDimensions( config.popups.length, config.max_cols ),
            img_url = url.replace('/assets/js','/assets/img/');
        
        editor.addButton( config.slug, { 
            text: config.text, 
            icon: config.icon, 
            title: config.title, 
            onclick: function() {
                
                // Open a window showing all icon boxes
                editor.windowManager.open( {
                    title: config.title,
                    width: dimensions.width,
                    height: dimensions.height,
                    body: [{
                        type: 'container',
                        html: genHTML( config.popups, config.max_cols )
                    }],
                    buttons: [] // Hide footer
                });
                 
                 // Click event for each icon box
                $('.afw-editor-popup-icon').click(function(){
                    
                    var id      = $(this).attr('data-id'),
                        title   = config.popups[id].title,
                        template= config.popups[id].template,
                        action  = config.slug + '_' + id,
                        width   = config.width,
                        height  = config.height,
                        disabled= $(this).hasClass('disabled'),
                        url     = ajaxurl + '?action=' + action;
                    
                    // Disabled button, do nothing
                    if( disabled )
                    {
                        return;
                    }
                    
                    // Close the previous dialog
                    editor.windowManager.close(); 
                    
                    // Open a new ajax popup form window
                    Amarkal.Editor.Form.open( editor, {
                        title: title,
                        url: url,
                        width: width,
                        height: height,
                        template: template
                    });
                });
            } 
        });
        
        /**
         * Generate the HTML for the icon boxes popup window.
         * 
         * @param {Object} buttons
         * @param {number} maxCols
         * @returns {String} The generated HTML.
         */
        function genHTML( buttons, maxCols )
        {
            var html = '';
            for( var i = 0; i < buttons.length; i++ )
            {
                html += '<div class="afw-editor-popup-icon'+(buttons[i].disabled ? ' disabled' : '')+'" title="'+buttons[i].label+'" data-id="'+i+'">';
                html += '<img src="'+img_url+buttons[i].img+'"/>';
                html += '<h3>'+buttons[i].label+'</h3>';
                html += '</div>';

                if( (i+1) % maxCols === 0 )
                {
                    html += '<br />';
                }
            }

            return html;
        }

        /**
         * Calculate the dimensions for the icon boxes popup window.
         * 
         * @param {number} boxCount
         * @param {number} maxCols
         * @returns {object} The calculated width and height of the window.
         */
        function calcDimensions( boxCount, maxCols )
        {
            var boxWidth    = 107,
                cols        = Math.min( maxCols, boxCount ),
                rows        = Math.ceil( boxCount / maxCols );
            return { width: cols*boxWidth+25, height: rows*boxWidth+25 };
        }
    });
    
    function isFullVersion( yes, no )
    {
        return 'Demo' === 'Full' ? yes : no;
    }
})(jQuery);