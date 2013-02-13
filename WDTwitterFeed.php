<?php
/*
Plugin Name: WD Twitter Feed
Plugin URI: http://www.webdesk.co.il/twitter-feed/
Description: An AJAXified Twitter feed widget
Version: 1.0
Author: Yoav Kadosh
Author URI: http://www.webdesk.co.il/
Author Email: yoavks@gmail.com
Text Domain: WDTwitterFeed-locale
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2012 WebDesk (admin@webdesk.co.il)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class WDTwitterFeed extends WP_Widget {
	
	// Public vars
	protected $widgetName = 'WDTwitterFeed';
	protected $widgetFancyName = 'WD Twitter Feed';
	protected $cssClass = 'wdtf';
	protected $textDomain = 'WDTwitterFeed-locale';
	protected $minHeight = 250;
	protected $minWidth = 220;
	private $options; // Holds widget options
	
	// Widget defaults
	private $defaults = array(
		'title' => 'My Tweets' ,
		'show_wrapper' => 'on', 
		'show_powered_by' => 'on',
		'numTweets' => 5 ,
		'codeBlock' => null ,
		'minHeight' => 250 ,
		'minWidth' => 220 
	);
	
	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget, 
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {
				
		// load plugin text domain
		add_action( 'init', array( $this, 'widget_textdomain' ) );
		
		// Initiate Ajax
		$this->initAjax();
			
		// Hooks fired when the Widget is activated and deactivated
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		parent::__construct(
			$this->widgetName ,
			__( $this->widgetFancyName , $this->textDomain ), // This is shown in the 'widgets' panel
			array(
				'classname'		=>	$this->widgetName ,
				'description'	=>	__( 'An AJAXified Twitter feed widget' , $this->textDomain )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
		
		// Fetch options from database
		$this->options = $this->get_widget_option('widget_'.$this->widgetName);

	} // end constructor
	
	// Get widget options
	function get_widget_option($option_name){
		if(!get_option( $option_name ))
			return null;
		
		$options = array_filter( get_option( $option_name ) );
		unset( $options['_multiwidget'] );
		
		foreach( $options as $key => $val )
			$options = $options[$key];
		
		return $options;
	}
		
	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/
	
	// Create hyperlinks from text
	function linkifyTweets($tweet, $blank = false) {
		// Open links in a new window
		$blank = $blank ? 'target="_blank"' : '';
		
		// Linkify URLs (hide http:// prefix)
		$tweet = preg_replace(
			'/(https?:\/\/(\S+))/',
			'<a href="\1" class="preg-links" '.$blank.'>\2</a>',
			$tweet
		);
		
		// Linkify twitter users
		$tweet = preg_replace(
			'/(^|\s)@(\w+)/',
			'\1@<a href="http://twitter.com/\2" class="preg-links" '.$blank.'>\2</a>',
			$tweet
		);
		
		// Linkify tags
		$tweet = preg_replace(
			'/(^|\s)#(\w+)/',
			'\1<a href="http://search.twitter.com/search?q=%23\2" class="preg-links" '.$blank.'>#\2</a>',
			$tweet
		);
 
		return $tweet;
	}
	
	//--------------------------------------
	// Create the tweets
	// $tweets = twitter timeline feed array
	// $align = blog direction ltr/rtl  
	//--------------------------------------
	function createTweets($tweets, $align) {
		// Create the tweets
		foreach($tweets as $tweet) {
			
			// Take retweeted info if this is a retweet
			if($tweet->retweeted_status) {
				$retweet = true;
				$retweeter = $tweet->user->name;
				// Replace tweet with retweeted status
				$tweet = $tweet->retweeted_status;
			} else $retweet = false;
			
			// Create a new datetime object
			$date = new DateTime($tweet->created_at);
			
			/* The tweet */ ?>
			<div class="tweet-wrapper">
				<img src="<?php echo $tweet->user->profile_image_url; ?>" width="32" height="32">
				<div class="user-card <?php echo $align; ?>">
					<time pubdate="" class="<?php echo $align; ?>" datetime="<?php echo $date->format($date::W3C); ?>" title=""><?php echo date_i18n(get_option('date_format'), $date->getTimestamp()); ?></time>
					<span class="user-name"><?php echo $tweet->user->name; ?></span>
					<a href="https://twitter.com/<?php echo $user; ?>" target="_blank" class="screen-name" dir="ltr">@<?php echo $tweet->user->screen_name; ?></a>
				</div>
				
				<?php /* Tweet text */ ?>
				<p><?php echo $this->linkifyTweets($tweet->text, true); ?></p>
			
				<?php /* Print retweet credits */ ?>
				<?php if($retweet) { ?>
				<p class="retweet-credits"><span class="<?php echo $align; ?>"></span><?php _e('Retweeted by ', $this->textdomain); echo $retweeter; ?></p>
				<?php } ?>
			
				<?php /* Tweet actions */ ?>
				<ul class="tweet-actions <?php echo $align; ?>">
					<li><a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id_str; ?>" class="reply-action web-intent" title="<?php _e('reply', $this->textdomain); ?>"></a></li>
					<li><a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id_str; ?>" class="retweet-action web-intent" title="<?php _e('retweet', $this->textdomain); ?>"></a></li>
					<li><a href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id_str; ?>" class="favorite-action web-intent" title="<?php _e('favorite', $this->textdomain); ?>"></a></li>
				</ul>
			</div>
	<?php } 
	}
	
	//--------------------------------------
	// The AJAX callback function
	//--------------------------------------
	public function twitterCallback() {
		
		$options = $this->options;
		$align = get_bloginfo('text_direction'); // Align elements by blog direction
		$height = $options['height'] ? $options['height'] : $this->defaults['minHeight'];
		$overflowHeight = $height - 60;
		$overflowHeight += $options['show_powered_by'] ? 0 : 15; // Add height if no PoweredBy
		
		// Declare content as html for ajax calls
		header('Content-Type: text/html; charset=utf-8');
		
		// Query twitter api to fetch the timeline of a user
		$data = 'https://api.twitter.com/1/statuses/user_timeline.json?'.
				'include_entities=true&'.
				'include_rts=true&'.
				'screen_name='.$options['user'].
				'&count='.$options['numTweets'];
		
		// Get the JSON data.
		$data = file_get_contents($data);
		
		// Convert the JSON data to PHP format.
		$tweets = json_decode($data); ?>
		
		<html dir="<?php echo $align; ?>">
		<link type="text/css" rel="Stylesheet" href="<?php echo plugins_url('style.css', __FILE__); ?>" />
		<script src="<?php echo plugins_url('script.js', __FILE__); ?>"></script>
		
		<div class="wrapper">
			<h1><i></i><?php echo $options['title']; ?></h1>
			
			<div class="tweets-overflow" style="height:<?php echo $overflowHeight; ?>px">
			
				<?php // Create tweets
				$this->createTweets($tweets, $align);?>
			
			</div>
			
			<?php /* Create gradient for custom height */ ?>
			<div class="gradient"></div>
			
			<?php /* Powered by link */ 
			if($options['show_powered_by']) { ?>
			<p class="poweredBy">Powered by <a href="http://webdesk.co.il">WebDesk</a></p>
			<?php } ?>
			
		</div>
		
		<?php
		exit;
	}
	
	// Initiate the callback function
	public function initAjax() {
		// Logged in users
		add_action('wp_ajax_twitterCallback', array($this, 'twitterCallback'));
		// No privileges users
		add_action('wp_ajax_nopriv_twitterCallback', array($this, 'twitterCallback'));
	}
	
	/**
	 * Outputs the content of the widget.
	 *
	 * @param	array	args		The array of form elements
	 * @param	array	instance	The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
		
		// Variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$showWrapper = $instance['show_wrapper'];
		$showPoweredBy = $instance['show_powered_by'];
		$ajax = $instance['enableAJAX'] ? true : false;
		$codeBlock = $instance['codeBlock'];
		$iframeSrc = admin_url('admin-ajax.php').'?action=twitterCallback';
		
		// This will be used to align the elements according to blog direction
		$align = get_bloginfo('text_direction');
		
		// Display the widget wrapper and title 
		if ( $showWrapper )
			echo $before_widget . $before_title . $title . $after_title;
		
		// Twitter.com widget code
		if($codeBlock) {
			echo $codeBlock;
			
			// Powered by link  
			if($showPoweredBy) {
				echo '<p class="poweredBy" style="margin:-5px 5px 10px">Powered by <a href="http://webdesk.co.il">WebDesk</a></p>';
			}
		}
		
		// Original widget code
		else { 
			
			// Load ajax loader and iframe style
			$this->ajax_loader_style(); ?>
			
			<div id="<?php echo $this->cssClass; ?>-ajax-loader"></div>
			
			<iframe id="<?php echo $this->cssClass; ?>-iframe" title="Twitter Timeline Widget" ></iframe>		

		<?php }
		
		// Display the last part of the wrapper
		echo ($showWrapper ? $after_widget : '');

	} // end widget

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param	array	new_instance	The previous instance of values before the update.
	 * @param	array	old_instance	The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
		// True for stripping tags and false otherwise
		$options = array(
			'title' => true,
			'user' => true,
			'show_wrapper' => true,
			'show_powered_by' => true,
			'numTweets' => true,
			'height' => true,
			'codeBlock' => false
		);
		
		foreach($options as $option => $strip) 
			$instance[$option] = $strip ? strip_tags( $new_instance[$option] ) : $new_instance[$option];
		
		return $instance;

	} // end widget
	
	/**
	 * Generates the administration form for the widget.
	 *
	 * @param	array	instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		
		// Initiate default values
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		
		/* Widget Title: Text Input.*/ ?>
		<div id="<?php echo $this->cssClass; ?>-admin-panel">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', $this->textDomain); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		
		<?php /* User */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'user' ); ?>"><?php _e('User name:', $this->textDomain); ?></label>
			<input id="<?php echo $this->get_field_id( 'user' ); ?>" name="<?php echo $this->get_field_name( 'user' ); ?>" type="text" value="<?php echo $instance['user']; ?>" class="widefat" />
		</p>
		
		<?php /* Show_wrapper checkbox */ ?>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_wrapper'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_wrapper' ); ?>" name="<?php echo $this->get_field_name( 'show_wrapper' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'show_wrapper' ); ?>"><?php _e('Display widget wrapper', $this->textDomain); ?></label>
		</p>
		
		
		<?php /* show_powered_by checkbox */ ?>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_powered_by'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_powered_by' ); ?>" name="<?php echo $this->get_field_name( 'show_powered_by' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'show_powered_by' ); ?>"><?php _e('Show "powered by" link (Thanks!)', $this->textDomain); ?></label>
		</p>
		    	
    	<?php /* Number of tweets */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'numTweets' ); ?>"><?php _e('Number of tweets:', $this->textDomain); ?></label>
			<input id="<?php echo $this->get_field_id( 'numTweets' ); ?>" name="<?php echo $this->get_field_name( 'numTweets' ); ?>" type="text" value="<?php echo $instance['numTweets']; ?>" class="widefat" />
		</p>
		
		<?php /* Max height */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>">
				<?php _e('Height (optional):', $this->textDomain); ?>
				<a href="#" class="tooltip" data-tip="If left blank, the widget will assume the default height (<?php echo $this->defaults['minHeight']; ?>px). Otherwise, the given height will be used and the content will become scrollable">(?)</a>
			</label>
			<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo $instance['height']; ?>" class="widefat" />
		</p>
		
		<?php /* Twitter.com code block */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'codeBlock' ); ?>">
				<?php _e('Twitter.com widget code (optional):', $this->textDomain); ?>
				<a href="#" class="tooltip" data-tip="Paste the widget code from twitter.com here. This will override the original widget">(?)</a>
			</label>
			<textarea dir="ltr" id="<?php echo $this->get_field_id( 'codeBlock' ); ?>" name="<?php echo $this->get_field_name( 'codeBlock' ); ?>" class="widefat" ><?php echo $instance['codeBlock']; ?></textarea>
		</p>
		</div>
				
	<?php

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {
		
		// Make plugin available for translation
		load_plugin_textdomain( $this->textDomain , false, plugin_dir_path( __FILE__ ) . '/languages/' );
		
	} // end widget_textdomain

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param		boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		// This will remove the db saved options
		delete_option( 'widget_'.$this->widgetName );		
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		wp_enqueue_style( $this->widgetName.'-admin-styles', plugins_url( 'admin.css', __FILE__ ) );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {

		wp_enqueue_script( $this->widgetName.'-admin-script', plugins_url( 'admin.js', __FILE__ ) );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		// Load the widget's style

	} // end register_widget_styles
	
	//--------------------------------------
	// Ajax loader and iframe style
	//--------------------------------------
	public function ajax_loader_style() { ?>
	<style>
		/* hide iframe before loading */
		iframe#wdtf-iframe {display:none;margin:10px 0}
		
		/* Ajax loader */
		div#wdtf-ajax-loader {
			width: 100%;height:100px;min-width:<?php echo $this->defaults['minWidth']; ?>px;
			background: rgb(48,223,241) url("<?php echo plugins_url( 'images/ajax-loader.gif' , __FILE__) ?>") no-repeat center;
			margin: 30px auto;
			
			border-radius: 4px;
			-moz-border-radius: 4px;
			-webkit-border-radius: 4px;
		}
	</style>
	<?php }
	
	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {
	
		// Load jQuery
		wp_enqueue_script('jquery');
		
		// Load the ajax call script
		wp_register_script( $this->widgetName.'-loader', plugins_url( 'loader.js', __FILE__ ), array('jquery'));  
		wp_enqueue_script($this->widgetName.'-loader');
		
		// Localize the script
		$options = $this->options;
		$options['src'] = admin_url('admin-ajax.php').'?action=twitterCallback';
		$options['minWidth'] = $this->defaults['minWidth'];
		$options['minHeight'] = $this->defaults['minHeight'];
		wp_localize_script($this->widgetName.'-loader' , $this->cssClass.'_loader_options' , $options);
		
	} // end register_widget_scripts

} // end class

// Register widget
add_action( 'widgets_init', create_function( '', 'register_widget("WDTwitterFeed");' ) ); 