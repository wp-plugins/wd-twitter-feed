<?php
/**
 * @package    twitterfeed
 * @date       Wed Oct 14 2015 01:03:50
 * @version    2.1.1
 * @author     Askupa Software <contact@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2015 Askupa Software
 */

namespace TwitterFeed\Widgets;

class StaticTweets extends \TwitterFeed\Widgets\Widget
{
    public static function get_components() 
    {
        return array_merge( 
            self::get_common_widget_components(), 
            self::get_common_tweet_ui_components('statictweets') 
        );
    }

    public static function get_name() 
    {
        return 'Static Tweets [TF]';
    }

    public static function render( $instance )
    {
        echo \TwitterFeed\static_tweets( $instance );
    }
}