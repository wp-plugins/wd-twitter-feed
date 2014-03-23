<?php
/**
 * Askupa Twitter Feed Plugin for Wordpress
 *
 * A simple-to-use yet powerful plugin that allows you to view your
 * tweets in various different ways and control the way they are fetched
 * and stored to improve site performance
 *
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 *
 * @wordpress-plugin
 * Plugin Name:		Askupa Twitter Feed
 * Plugin URI:		http://products.askupasoftware.com/twitter-feed
 * Description:		A simple-to-use yet powerful plugin that allows you to view your tweets in various different ways
 * Version:			1.3
 * Version Type:	Demo
 * Author:			Askupa Software
 * Author URI:		http://www.askupasoftware.com
 * Text Domain:		askupatwitterfeed-locale
 * Domain Path:		/languages
 */

namespace TWITTERFEED;require_once( plugin_dir_path( __FILE__ ) . 'lib/askupa/amarkal/Amarkal.class.php' );
require_once( plugin_dir_path( __FILE__ ) . 'config/plugin/AskupaTwitterFeed.class.php' );AskupaTwitterFeed::get_instance(__FILE__);

