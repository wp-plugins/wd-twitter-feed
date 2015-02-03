<?php
/**
 * Twitter Feed
 *
 * Powerful Twitter Integration System for WordPress.
 *
 * @package   Twitter Feed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 *
 * @wordpress-plugin
 * Plugin Name:     Twitter Feed
 * Plugin URI:      http://products.askupasoftware.com/twitter-feed/
 * Description:     Powerful Twitter Integration System for WordPress.
 * Version:         2.0.0
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
        if ( $validator->is_valid( 'plugin' ) )
        {
            require_once 'app/TwitterFeed.php';
        }
    }
    add_action( 'plugins_loaded', 'twitter_feed_bootstrap' );
}
