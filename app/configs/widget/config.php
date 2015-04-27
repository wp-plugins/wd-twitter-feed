<?php

namespace TwitterFeed;

use Amarkal\Extensions\WordPress\Widget;
use Amarkal\UI\Components;

return new Widget\WidgetConfig(array(
    'name'          => 'Twitter Feed Widget',
    'version'       => PLUGIN_VERSION,
    'languages-url' => PLUGIN_DIR . 'languages/',
    'description'   => 'Use this widget to display tweets in your widget area',
    'callback'      => function( $args, $instance ) {
        
        extract($args, EXTR_SKIP);

        $title = apply_filters('widget_title', $instance['title']);

        // Display the widget wrapper and title 
        if ($instance['wrapper'] == 'ON')
        {
            echo $before_widget;
        }

        if (!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        if (!empty($instance['subtitle']))
        {
            echo $instance['subtitle'];
        }
        
        
        $instance['show'] = explode(',', $instance['show']);
        $instance['replies'] = $instance['replies'] == 'ON';
        $instance['retweets'] = $instance['retweets'] == 'ON';
                
        // Render the tweets
        echo static_tweets( $instance );

        // Display the last part of the wrapper
        echo ( $instance['wrapper'] == 'ON' ? $after_widget : '' );
    }, 
    'fields' => array(
        /*
        new UI\Seperator(array(
            'label'            => 'Widget Appearance'
        )),*/
        new Components\Text(array(
            'name'          => 'title',
            'title'         => 'Widget Title',
            'default'       => 'My Tweets',
            'filter'        => function( $v ) { return trim( strip_tags( $v ) ); },
            'help'          => 'The widget\'s title appears at the top of the widget'
        )),
        new Components\Text(array(
            'name'          => 'subtitle',
            'title'         => 'Widget Subtitle',
            'placeholder'   => 'Some extra words...',
            'help'          => 'The widget\'s subtitle appears under the widget\'s title'
        )),
        new Components\ToggleButton(array(
            'name'          => 'wrapper',
            'help'          => 'Show/hide the theme default widget wrapper',
            'title'         => 'Display Widget Wrapper',
            'default'       => 'ON'
        )),/*
        new UI\Seperator(array(
            'label'            => 'Query'
        )),*/
        new Components\DropDown(array(
            'name'          => 'resource',
            'title'         => 'Resource',
            'default'       => 'usertimeline',
            'help'          => 'Choose the twitter resource type',
            'options'       => array(
                    'Resource_usertimeline'      => 'User Timeline'
                ) + _is_full_version(array(
                    'Resource_hometimeline'      => 'Home Timeline',
                    'Resource_mentions'          => 'Mentions Timeline',
                    'Resource_retweets'          => 'Retweets of Me',
                    'Resource_list'              => 'List',
                    'Resource_search'            => 'Search',
                    'favorites'                  => 'Favorites'
                ), array()
            )
        )),
        new Components\Text(array(
            'name'      => 'user',
            'title'     => 'User Name',
            'default'   => '',
            'help'      => 'Specify the Twitter user name of which the tweets will be shown'
        )),
        new Components\Text(array(
                'name'          => 'list',
                'title'         => 'List Name',
                'help'          => 'Specify the Twitter user name of which the tweets will be shown'
            ) + _is_full_version( array(), array(
                'placeholder'   => 'Not available in the demo version',
                'disabled'      => true
            )
        )),
        new Components\Text(array(
                'name'          => 'query',
                'title'         => 'Search Query',
                'help'          => 'Specify the Twitter list name of which the tweets will be shown'
            ) + _is_full_version( array(), array(
                'placeholder'   => 'Not available in the demo version',
                'disabled'      => true
            )
        )),
        new Components\Spinner(array(
            'name'          => 'count',
            'title'         => 'Number of Tweets',
            'help'          => 'Set the number of tweets',
            'default'       => 5,
            'min'           => 1,
            'max'           => _is_full_version(800,20)
        )),
        new Components\ToggleButton(array(
            'name'          => 'replies',
            'title'         => 'Show Replies',
            'default'       => 'ON',
        )),
        new Components\ToggleButton(array(
            'name'          => 'retweets',
            'title'         => 'Show Retweets',
            'default'       => 'ON',
        )),/*
        new Components\Seperator(array(
            'label'            => 'Tweet Styling'
        )),*/
        new Components\DropDown(array(
            'name'          => 'skin',
            'title'         => 'Tweet Skin',
            'default'       => 'default',
            'options'       => array(
                    'default'       => 'Default' ,
                    'simplistic'    => 'Simplistic',
                ) + _is_full_version(array(
                    'futuristic'    => 'Futuristic',
                    'talk-bubble'   => 'Talk Bubble',
                ), array())
        ))/*,
        new Components\Seperator(array(
            'label'            => 'Tweet Assets'
        ))*/,
        new Components\Checkbox(array(
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
                'media'         => 'Media'
            )
        ))
    )
));