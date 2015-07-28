<?php
/**
 * @package    twitterfeed
 * @date       Tue Jul 28 2015 14:02:04
 * @version    2.0.8
 * @author     Askupa Software <contact@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2014 Askupa Software
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