<?php

use Amarkal\UI;

$common = array(
    'user' => new UI\Components\Text(array(
        'name'          => 'user',
        'title'         => 'User Name',
        'description'   => 'Enter a Twitter.com user name',
        'help'          => 'This field is only applicable if the twitter resource type is set to "User Timeline" or "List". Enter the Twitter accout username of which you would like to display the tweets',
    )),
    'list' => new UI\Components\Text(array(
        'name'          => 'list',
        'title'         => 'List Name',
        'description'   => 'Enter a Twitter.com list name',
        'help'          => 'This field is only applicable if the twitter resource type is set to "List".  ',
    )),
    'query' => new UI\Components\Text(array(
        'name'          => 'query',
        'title'         => 'Search Query',
        'description'   => 'Enter a search query',
        'help'          => 'This field is only applicable if the twitter resource type is set to "Search". For more information about the query syntax, visit Twitter\'s search guide: https://dev.twitter.com/docs/using-search',
        'placeholder'   => 'from:username AND #hashtag OR @username'
    )),
    'resource' => new UI\Components\DropDown(array(
        'name'          => 'resource',
        'title'         => 'Resource',
        'description'   => 'Choose the twitter resource type',
        'default'       => 'usertimeline',
        'options'       => array(
            'usertimeline' => 'User Timeline'
        ) + TwitterFeed\_is_full_version(array(
            'hometimeline'      => 'Home Timeline',
            'mentions'          => 'Mentions Timeline',
            'retweets'          => 'Retweets of Me',
            'list'              => 'List',
            'search'            => 'Search'
        ),array())
    )),
    'count' => new UI\Components\Spinner(array(
        'name'          => 'count',
        'title'         => 'Count',
        'description'   => 'Number of tweets to display',
        'min'           => 1,
        'max'           => TwitterFeed\_is_full_version(800, 20),
        'default'       => 5,
    ))
);

return new Amarkal\Extensions\WordPress\Editor\Plugins\IconBoxPopup(array(
    'slug'      => 'twitterfeed_button',
    'text'      => null,
    'icon'      => 'fa fa-twitter',
    'title'     => 'Add a Twitter Feed Widget',
    'row'       => 1,
    'max_cols'  => 3,
    'buttons'   => array(
        
        /**
         * Static Tweets
         */
        array(
            'img'       => TwitterFeed\IMG_URL.'/static-icon.gif',
            'label'     => 'Static Tweets',
            'title'     => 'Insert Static Tweets',
            'width'     => 600,
            'height'    => 450,
            'template'  => '[statictweets skin="<% skin %>" resource="<% resource %>" user="<% user %>" list="<% list %>" query="<% query %>" count="<% count %>" show="<% show %>"/]',
            //'callback'  => function() {},
            'fields'    => array(
                new UI\Components\DropDown(array(
                    'name'          => 'skin',
                    'title'         => 'Skin',
                    'description'   => 'Choose how to skin the tweets',
                    'default'       => 'default',
                    'options'       => array(
                        'default'      =>'Default',
                        'simplistic'   =>'Simplistic'
                    ) + TwitterFeed\_is_full_version(array(
                        'futuristic'   => 'Futuristic',
                        'talk-bubble'  => 'Talk Bubble'
                    ), array())
                )),
                $common['resource'],
                $common['user'],
                $common['list'],
                $common['query'],
                $common['count'],
                new UI\Components\Checkbox(array(
                    'name'          => 'show',
                    'title'         => 'Show options',
                    'help'          => 'Select which tweet assets you would like to show',
                    'default'       => 'username,screenname,avatar,time,actions,replies,retweets,media',
                    'options'       => array(
                        'username'      => 'User Name',
                        'screenname'    => 'Screen Name',
                        'avatar'        => 'Avatar',
                        'time'          => 'Tweet Time',
                        'actions'       => 'Tweet Actions',
                        'replies'       => 'Replies',
                        'retweets'      => 'Retweets',
                        'media'         => 'Media'
                    )
                ))
            )
        ),
        
        /**
         * Scrolling Tweets
         */
        array(
            'img'       => TwitterFeed\IMG_URL.'/scrolling-icon.gif',
            'label'     => 'Scrolling Tweets',
            'title'     => 'Insert Scrolling Tweets',
            'disabled'  => TwitterFeed\_is_full_version(false, true),
            'width'     => 600,
            'height'    => 450,
            'template'  => '[scrollingtweets scroll_time="<% scroll_time %>" skin="<% skin %>" resource="<% resource %>" user="<% user %>" list="<% list %>" query="<% query %>" count="<% count %>"/]',
            //'callback'  => function() {}
            'fields'    => array(
                new UI\Components\Spinner(array(
                    'name'          => 'scroll_time',
                    'title'         => 'Scrolling Time',
                    'description'   => 'Set the duration of each slide in seconds',
                    'min'           => 5,
                    'max'           => 120,
                    'step'          => 1,
                    'default'       => 10
                )),
                $common['resource'],
                $common['user'],
                $common['list'],
                $common['query'],
                $common['count'],
                new UI\Components\DropDown(array(
                    'name'          => 'skin',
                    'title'         => 'Skin',
                    'description'   => 'Choose how to skin the tweets',
                    'help'          => 'Note: for the LED Screen skin, use a longer scrolling time since the font size is bigger and it takes longer to scroll through the tweet',
                    'default'       => 'default',
                    'options'       => array(
                        'default'      => 'Default',
                        'led-screen'   => 'LED Screen'
                    )
                ))
            )
        ),
        
        /**
         * Sliding Tweets
         */
        array(
            'img'       => TwitterFeed\IMG_URL.'/sliding-icon.gif',
            'label'     => 'Sliding Tweets',
            'title'     => 'Insert Scrolling Tweets',
            'disabled'  => TwitterFeed\_is_full_version(false, true),
            'width'     => 600,
            'height'    => 450,
            'template'  => '[slidingtweets slide_dir="<% slide_dir %>" slide_duration="<% slide_duration %>" skin="<% skin %>" resource="<% resource %>" user="<% user %>" list="<% list %>" query="<% query %>" count="<% count %>" show="<% show %>"/]',
            //'callback'  => function() {}
            'fields'    => array(
                new UI\Components\DropDown(array(
                    'name'          => 'slide_dir',
                    'title'         => 'Slide Direction',
                    'description'   => 'Set the direction of the slide',
                    'default'       => 'right',
                    'options' => array(
                        'up'        => 'Up',
                        'down'      => 'Down',
                        'left'      => 'Left',
                        'right'     => 'Right',
                        'random'    => 'Random'
                    )
                )),
                new UI\Components\Spinner(array(
                    'name'          => 'slide_duration',
                    'title'         => 'Slide Duration',
                    'description'   => 'Set the duration of each slide in seconds',
                    'min'           => 2,
                    'max'           => 120,
                    'step'          => 1,
                    'default'       => 5
                )),
                $common['resource'],
                $common['user'],
                $common['list'],
                $common['query'],
                $common['count'],
                new UI\Components\DropDown(array(
                    'name'          => 'skin',
                    'title'         => 'Skin',
                    'description'   => 'Choose how to skin the tweets',
                    'default'       => 'default',
                    'options'       => array(
                        'default'      =>'Default',
                        'simplistic'   =>'Simplistic'
                    ) + TwitterFeed\_is_full_version(array(
                        'futuristic'   => 'Futuristic',
                        'talk-bubble'  => 'Talk Bubble'
                    ), array())
                )),
                new UI\Components\Checkbox(array(
                    'name'          => 'show',
                    'title'         => 'Show options',
                    'help'          => 'Select which tweet assets you would like to show',
                    'default'       => 'username,screenname,avatar,time,actions,replies,retweets',
                    'options'       => array(
                        'username'      => 'User Name',
                        'screenname'    => 'Screen Name',
                        'avatar'        => 'Avatar',
                        'time'          => 'Tweet Time',
                        'actions'       => 'Tweet Actions',
                        'replies'       => 'Replies',
                        'retweets'      => 'Retweets'
                    )
                ))
            )
        )
    )
));
