TwitterFeed.settings = {
    popUpWidth:  700,
    popUpHeight: 345
};

TwitterFeed.init = function() 
{
    // Bind onclick tweet actions
    $('.atf-web-intent').each(function() {
        $(this).click(function(e) {
            e.preventDefault(); // Prevent the link from being opened
            newwindow = window.open(
                this.getAttribute("href"),
                this.getAttribute("title"),
                'height=' + TwitterFeed.settings.popUpHeight + ',width=' + TwitterFeed.settings.popUpWidth
            );

            // Focus
            if(window.focus) { newwindow.focus(); }

            // Centralize the popup window
            newwindow.moveTo((screen.width-TwitterFeed.settings.popUpWidth)/2,(screen.height-TwitterFeed.settings.popUpHeight)/2);
            return false;
        });
    });

    // Show hide media
    $('.atf-show-media-button').click(function(e) {
        e.preventDefault();
        var tweet = $(this).parent();
        tweet.find('.atf-media-wrapper').toggle(300, function(){
            var el = tweet.find('.atf-show-media-button > span');
            var text = el.text() === 'Show' ? 'Hide' : 'Show';
            el.text(text);
        });
    });

    // Hide debug window onclick
    $('#twitter-feed-debug-window .close-button').click(function() {
        $('#twitter-feed-debug-window').hide();
    });
};
