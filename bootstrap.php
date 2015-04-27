<?php
/**
 * Twitter Feed
 *
 * A powerful Twitter integration system that allows you to display tweets using widgets and shortcodes
 *
 * @package   Twitter Feed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 *
 * @wordpress-plugin
 * Plugin Name:     Twitter Feed
 * Plugin URI:      http://products.askupasoftware.com/twitter-feed/
 * Description:     A powerful Twitter integration system that allows you to display tweets using widgets and shortcodes
 * Version:         2.0.5
 * Author:          Askupa Software
 * Author URI:      http://www.askupasoftware.com
 * Text Domain:     twitterfeed
 * Domain Path:     /languages
 */

if( !function_exists('twitter_feed_bootstrap') )
{
    function twitter_feed_bootstrap()
    {
        $validator = require_once 'vendor/askupa-software/amarkal-framework/EnvironmentValidator.php';
        $validator->add_plugin( 'Twitter Feed', dirname( __FILE__ ).'/app/TwitterFeed.php' );
    }
    twitter_feed_bootstrap();
}
