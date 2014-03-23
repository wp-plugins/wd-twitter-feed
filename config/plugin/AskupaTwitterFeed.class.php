<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

class AskupaTwitterFeed extends Amarkal {

	private $options;	protected function __construct() {		parent::init();		add_action('widgets_init', create_function('', 'return register_widget("TWITTERFEED\\AskupaTwitterFeedWidget");'));		$this->options = TwitterFeedOptions::get_instance();		add_filter( 'widget_text', 'do_shortcode' );		add_action('init', array( $this, 'add_shortcodes_button' ) );		$this->register_shortcodes();
	}
	
	/*------------------------------------------------------*/
	/* Called upon when the plugin is activated
	/*------------------------------------------------------*/
	public static function activate( $network_wide ) {}
	
	/*------------------------------------------------------*/
	/* Called upon when the plugin is deactivated
	/*------------------------------------------------------*/
	public static function deactivate( $network_wide ) {}

	/*------------------------------------------------------*/
	/* Register and enqueue admin-specific style sheet.
	/*
	/* @return    null    Return early if no settings page 
	/*					  is registered.
	/*------------------------------------------------------*/
	public function enqueue_admin_styles() {
		parent::enqueue_admin_styles();
	}


	/*------------------------------------------------------*/
	/* Register and enqueue admin-specific JavaScript.
	/*
	/* @return    null    Return early if no settings page is registered.
	/*------------------------------------------------------*/
	public function enqueue_admin_scripts() {
		wp_enqueue_script('jquery');
		parent::enqueue_admin_scripts();
	}


	/*------------------------------------------------------*/
	/* Register and enqueue public-facing style sheet.
	/*------------------------------------------------------*/
	public function enqueue_public_styles() {
		wp_enqueue_style( 'font-awsome', 'http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', array(), '4.0.3' );
		parent::enqueue_public_styles();
	}

	/*------------------------------------------------------*/
	/* Register and enqueues public-facing JavaScript files.
	/*------------------------------------------------------*/
	public function enqueue_public_scripts() {
		wp_enqueue_script('jquery');
		parent::enqueue_public_scripts();
	}
	
	/*------------------------------------------------------*/
	/* Register Shortcodes
	/*------------------------------------------------------*/
	private function register_shortcodes() {
		$shortcodes = Shortcode::get_instance();
		$shortcodes->register();
	}
	
	/**
	 * Localize the data
	 * @return array
	 */
	public function get_local_data() {		return array(
			array(
				'handle' => 'widget',
				'object' => 'askupa_twitter_feed',
				'array' => array( 'plugin_url' => PLUGIN_URL )
			)
		);
	}
	
	/*------------------------------------------------------*/
	/* Add Shortcodes Button
	/* Adds and registers the button into the tinyMCE rich 
	/* editing panel
	/*------------------------------------------------------*/
	function add_shortcodes_button() {
		
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;
		if ( get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array( $this, 'add_shortcodes_tinymce_plugin' ) );
			add_filter('mce_buttons', array( $this, 'register_shortcodes_button' ) );
		}
		
		add_filter( 'tiny_mce_version', array( $this, 'refresh_mce' ) );
	}
		
	function register_shortcodes_button($buttons) {
		array_push($buttons, "|", "tweeterfeed");
		return $buttons;
	}

	function add_shortcodes_tinymce_plugin($plugin_array) {
		$plugin_array['tweeterfeed'] = PLUGIN_URL . 'assets/js/plugin.js';
		return $plugin_array;
	}
	
	function refresh_mce($ver) {
		$ver += 3;
		return $ver;
	}

	public function get_tinymce_menu_items() {
		print(json_encode(array(
			array(
				'name' => 'one',
				'label' => 'One'
			),
			array(
				'name' => 'two',
				'label' => 'Two'
			),
		)));
		die();
	}
}