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
 * Plugin Name:		Askupa Twitter Feed (Demo)
 * Plugin URI:		http://products.askupasoftware.com/twitter-feed
 * Description:		Powerful Twitter Integration System for WordPress 
 * Version:			1.41
 * Version Type:	Demo
 * Author:			Askupa Software
 * Author URI:		http://www.askupasoftware.com
 * Text Domain:		askupatwitterfeed
 * Domain Path:		/languages
 */

namespace TWITTERFEED;

// Load Amarkal and the plugin class file
require_once( plugin_dir_path( __FILE__ ) . 'lib/askupa/amarkal/Amarkal.class.php' );
require_once( plugin_dir_path( __FILE__ ) . 'config/plugin/AskupaTwitterFeed.class.php' );

// Get an instance of the class to initiate it
AskupaTwitterFeed::get_instance(__FILE__,__NAMESPACE__);

