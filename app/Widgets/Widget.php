<?php

namespace TwitterFeed\Widgets;

use Amarkal\UI\Components;

abstract class Widget
{
    public static function register()
    {
        $widget = new \Amarkal\Extensions\WordPress\Widget\Widget( self::get_config() );
        $widget->register(); 
    }
    
    public static function get_config()
    {
        return new \Amarkal\Extensions\WordPress\Widget\WidgetConfig(array(
            'name'          => static::get_name(),
            'version'       => \TwitterFeed\PLUGIN_VERSION,
            'languages-url' => \TwitterFeed\PLUGIN_DIR . 'languages/',
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
                $instance['resource'] = 'Resource_'.$instance['resource'];

                // Render the tweets
                static::render( $instance );

                // Display the last part of the wrapper
                echo ( $instance['wrapper'] == 'ON' ? $after_widget : '' );
            }, 
            'fields' => static::get_components()
        ));
    }
    
    public static function get_common_widget_components()
    {
        return array(
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
            ))
        );
    }
    
    static function get_common_tweet_ui_components( $type )
    {
        $common = include( dirname( __DIR__ ).'/configs/common.php' );
        return $common[$type];
    }
    
    public static function get_name()
    {
        throw new \RuntimeException("Unimplemented");
    }
    
    public static function get_components()
    {
        throw new \RuntimeException("Unimplemented");
    }
    
    public static function render( $instance )
    {
        throw new \RuntimeException("Unimplemented");
    }
}


