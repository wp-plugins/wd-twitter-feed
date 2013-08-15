<?php
/*
Plugin Name: WD Twitter Feed
Plugin URI: http://www.webdesk.co.il/twitter-feed/
Description: A simple and powerful Twitter feed widget.
Version: 1.2.0
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
	protected $apiUrl = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	protected $tokens = 'twitterFeedTokens'; // Used as the option's field name 
	protected $timeout = 5;
	private $options; // Holds widget options
	
	// Widget default options
	private $defaults = array(
		'title' => 'My Tweets' ,
		'titleColor' => '#1b90ad' ,
		'bodyColor' => '#1ea9cc' ,
		'user' => '' ,
		'show_wrapper' => 'on', 
		'show_powered_by' => 'on',
		'replies' => 'on',
		'retweets' => 'on',
		'numTweets' => 5 ,
		'cacheFreq' => 24 ,
		'twitter_widget_code' => null
	);
	
	// List of options that would not be stripped from tags
	private $noStrip = array( 'twitter_widget_code' );
		
	
	/*--------------------------------------------------*/
	/* Constructor
	/*
	/* Specifies the classname and description, instantiates 
	/* the widget, loads localization files, and includes
	/* necessary stylesheets and JavaScript.
	/*--------------------------------------------------*/
	public function __construct() {
		
		// Load neccessary files
		require_once('twitter-api-exchange.php');
		require_once('admin-menu.php');
			
		// load plugin text domain
		add_action( 'init', array( $this, 'widget_textdomain' ) );
			
		// Hooks fired when the Widget is activated and deactivated
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		parent::__construct(
			$this->widgetName ,
			__( $this->widgetFancyName , $this->textDomain ), // This is shown in the 'widgets' panel
			array(
				'classname'		=>	$this->widgetName ,
				'description'	=>	__( 'A simple and powerful Twitter feed widget.' , $this->textDomain )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
		
		// Fetch options and access tokens from database
		$this->options = $this->get_widget_option('widget_'.$this->widgetName);
		$this->tokens = get_option( $this->tokens );

	} // end constructor
	
	/*--------------------------------------------------*/
	/* Get widget options
	/*
	/* Returns an array of the widget options after unsetting
	/* `multiwidget` 
	/*--------------------------------------------------*/
	function get_widget_option($option_name){
		if(!get_option( $option_name ))
			return null;
		
		$options = array_filter( get_option( $option_name ) );
		unset( $options['_multiwidget'] );
		
		foreach( $options as $key => $val )
			$options = $options[$key];
		
		return $options;
	} // End getWidgetOptions
	
	/*--------------------------------------------------*/
	/* Fetch Feed
	/*
	/* Fetches the feed and handles the errors. If the user 
	/* has specified caching freuency, the function would 
	/* claculate the elapsed time from last fetch and would 
	/* use the last cached feed if the elapsed time is smaller 
	/* than the specified chaching frequency. Otherwise, the 
	/* function would make a twitter request to fetch the live data.
	/*--------------------------------------------------*/
	public function fetchFeed() {
		
		// Caching was enabled
		if($this->options['cacheFreq']) {
			
			// Check how much time has passed since the last cache
			$elapsed = time() - get_option('twitterFeedCacheTime');
						
			// Elapsed time from last call is greater 
			// than the specified caching frequency			
			if($elapsed > $this->options['cacheFreq']) {
				// Make a call to Twitter
				$resp = $this->makeTwitterRequest();
				
				// Cache
				update_option( 'twitterFeedCache', $resp );
				
				// Update last cache time
				update_option( 'twitterFeedCacheTime', time() );
			}
			
			// Elapsed time from last call is NOT greater 
			// than the specified caching frequency	
			else {
				$resp = get_option('twitterFeedCache');		
			}
			
			// Return data
			return $resp;
		}
		
		// Caching is disabled
		else 
			return $this->makeTwitterRequest();
		
	} // End fetchFeed
	
	/*--------------------------------------------------*/
	/* Make Twitter Request
	/*
	/* This function uses Twitter API Exchange to make a 
	/* twitter request and retrieve the latest tweets.
	/* Returns an array of tweets.
	/* NOTE: This function does not throw errors!
	/*--------------------------------------------------*/
	public function makeTwitterRequest() {
			
		// Build the argument list
		$args = add_query_arg( array( 'screen_name' => $this->options['user'] ), $args );
		
		if ( $this->options['numTweets'] )
			$args = add_query_arg( array( 'count' => $this->options['numTweets'] ), $args );

		if ( !$this->options['replies'] )
			$args = add_query_arg( array( 'exclude_replies' => 'true' ), $args );

		if ( $this->options['retweets'] )
			$args = add_query_arg( array( 'include_rts' => 'true' ), $args );
		
		// Perform request
		$twitter = new TwitterAPIExchange($this->tokens);
		$resp = $twitter->setGetfield($args)
						->buildOauth($this->apiUrl, 'GET')
						->performRequest();
				
		// Return decoded response
		return json_decode( $resp );
	}
	
	/* Widget API Functions */
	
	/*--------------------------------------------------*/
	/* Linkify Tweets
	/* Create hyperlinks from text
	/*--------------------------------------------------*/
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
	} // End linkifyTweets
	
	/*--------------------------------------------------*/
	/* Display Tweets
	/* 
	/* This function displays the list of tweets and checks 
	/* for errors. If any error occur, the proper message 
	/* would be displayed in the widget.
	/*--------------------------------------------------*/
	function displayTweets() {
		
		// No user was specified - bail early and throw an error
		if(!$this->options['user'])
			$errors = 'Please specify a screen name in the widget panel.';
		
		// No credentials
		if(!$this->tokens || !get_option('twitterFeedTokens'))
			$errors = 'Please specify the oAuth credentials in the widget panel.';
		
		// Fetch the feed and check for errors
		$tweets = $this->fetchFeed();
		
		// Error handling
		if( $tweets->errors ) {
			foreach( $tweets->errors as $error ) {
				$errors = $error->message . '<br />';
				$errors .= 'Code: ' . $error->code . '<br />';
			}
		}
		
		// Display errors if neccessary
		if($errors) {
			echo '<div class="wdtf-wrapper">';
			echo $errors;
			echo '</div>';
			return;
		}
						
		
		// Continue with displaying tweets
		$align = get_bloginfo('text_direction'); // Align elements by blog direction
		$options = $this->options;
						
		echo '<div class="wdtf-wrapper" style="background-color:' . $options['bodyColor'] . '">';
		echo '<h3 style="color:' . $options['titleColor'] . '"><i></i>'. $options['title'] . '</h3>';
		//echo '<div class="tweets-overflow" style="height:' . $overflowHeight . 'px">';
		
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
			<div class="wdtf-tweet-wrapper">
				
				<?php /* Time */ ?>
				<time class="<?php echo $align; ?>" pubdate="" datetime="<?php echo $date->format($date->W3C); ?>" title="">
					<?php echo date_i18n(get_option('date_format'), $date->getTimestamp()); ?>
				</time>
				
				<?php /* User Card */ ?>
				<div class="wdtf-user-card <?php echo $align; ?>">
					<img src="<?php echo $tweet->user->profile_image_url; ?>" width="32" height="32">
					<div class="wdtf-screen-name">
						<span><?php echo $tweet->user->name; ?></span><br />
						<a href="https://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank" dir="ltr">@<?php echo $tweet->user->screen_name; ?></a>
					</div>
				</div>
				
				<?php /* Tweet text */ ?>
				<p class="wdtf-tweet-text"><?php echo $this->linkifyTweets($tweet->text, true); ?></p>
			
				<?php /* Print retweet credits */ ?>
				<?php if($retweet) { ?>
				<p class="wdtf-retweet-credits"><span class="<?php echo $align; ?>"></span><?php _e('Retweeted by ', $this->textdomain); echo $retweeter; ?></p>
				<?php } ?>
			
				<?php /* Tweet actions */ ?>
				<ul class="wdtf-tweet-actions <?php echo $align; ?>">
					<li><a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id_str; ?>" class="reply-action web-intent" title="<?php _e('reply', $this->textdomain); ?>"></a></li>
					<li><a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id_str; ?>" class="retweet-action web-intent" title="<?php _e('retweet', $this->textdomain); ?>"></a></li>
					<li><a href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id_str; ?>" class="favorite-action web-intent" title="<?php _e('favorite', $this->textdomain); ?>"></a></li>
				</ul>
			</div>
	<?php 
		} // End foreach
		echo '</div>';
	} // End displayTweets

	/*--------------------------------------------------*/
	/* Outputs the content of the widget.
	/*
	/* @param	array	args		The array of form elements
	/* @param	array	instance	The current instance of the widget
	/*--------------------------------------------------*/
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
				
		// This will be used to align the elements according to blog direction
		$align = get_bloginfo('text_direction');
		
		// Display the widget wrapper and title 
		if ( $instance['show_wrapper'] )
			echo $before_widget ;
		
		// Use twitter.com widget code
		if( $instance['twitter_widget_code'] )
			echo $instance['twitter_widget_code'];
		
		// Render the tweets
		else $this->displayTweets();
		
		// Show powered by
		if( $instance['show_powered_by'] )
			echo '<p class="wdtf-powered-by" id="wdtf-powered-by">Powered by <a href="http://webdesk.co.il">WebDesk</a></p>';
		
		// Display the last part of the wrapper
		echo ( $instance['show_wrapper'] ? $after_widget : '' );

	} // End widget

	/*--------------------------------------------------*/
	/* Processes the widget's options to be saved.
	/*
	/* @param	array	new_instance	The previous instance 
	/*									of values before the update.
	/* @param	array	old_instance	The new instance of 
	/*									values to be generated via the update.
	/*--------------------------------------------------*/
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
		// Loop through the list of options, strip tags if needed and update
		foreach(array_keys( $this->defaults ) as $option) {
			$updated = false;
			
			// Update options that dont require tag stripping
			foreach(array_keys( $this->noStrip ) as $noStriping) {
				if($option == $noStriping) {
					$instance[$option] = $new_instance[$option];
					$updated = true;
					break;
				}
			}
			
			// Update options that require tag stripping
			if(!$updated)
				$instance[$option] = strip_tags( $new_instance[$option] );
		}
		
		return $instance;

	} // End update widget
	
	/*--------------------------------------------------*/
	/* Generates the administration form for the widget.
	/*
	/* @param	array	instance	The array of keys and 
	/*								values for the widget.
	/*--------------------------------------------------*/
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
			<input id="twitter-username" name="<?php echo $this->get_field_name( 'user' ); ?>" type="text" value="<?php echo $instance['user']; ?>" class="widefat" />
			<input type="text" disabled="disabled" value="Please enter a valid user name above" class="widefat user-validator" />
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
		
		<?php /* Show replies */ ?>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['replies'], 'on' ); ?> id="<?php echo $this->get_field_id( 'replies' ); ?>" name="<?php echo $this->get_field_name( 'replies' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'replies' ); ?>"><?php _e('Show replies', $this->textDomain); ?></label>
		</p>
		
		<?php /* Show retweets */ ?>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['retweets'], 'on' ); ?> id="<?php echo $this->get_field_id( 'retweets' ); ?>" name="<?php echo $this->get_field_name( 'retweets' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'retweets' ); ?>"><?php _e('Show retweets', $this->textDomain); ?></label>
		</p>
		    	
    	<?php /* Number of tweets */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'numTweets' ); ?>"><?php _e('Number of tweets:', $this->textDomain); ?></label>
			<input id="<?php echo $this->get_field_id( 'numTweets' ); ?>" name="<?php echo $this->get_field_name( 'numTweets' ); ?>" type="number" min="1" max="1000" step="1" value="<?php echo $instance['numTweets']; ?>" class="small-text widefat alignright" />
		</p>
		
		<?php /* Caching frequency */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'cacheFreq' ); ?>">
				<?php _e('Caching freq. (optional):', $this->textDomain); ?>
				<a href="#" class="tooltip" data-tip="Use this to specify the caching frequency (in seconds) to avoid Twitter rate limits. If left blank, no caching will occur">(?)</a>
			</label>
			<input id="<?php echo $this->get_field_id( 'cacheFreq' ); ?>" name="<?php echo $this->get_field_name( 'cacheFreq' ); ?>" type="number" min="24" max="1024" step="2" value="<?php echo $instance['cacheFreq']; ?>" class="small-text widefat alignright" />
		</p>
		
		<?php /* Title Color Selector */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'titleColor' ); ?>"><?php _e('Title Color', $this->textDomain); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'titleColor' ); ?>" name="<?php echo $this->get_field_name( 'titleColor' ); ?>" type="text" value="<?php echo $instance['titleColor']; ?>" class="wd-color-field widefat" data-default-color="#ffffff" />
		</p>
		
		<?php /* Body Color Selector */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'bodyColor' ); ?>"><?php _e('Body Color', $this->textDomain); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'bodyColor' ); ?>" name="<?php echo $this->get_field_name( 'bodyColor' ); ?>" type="text" value="<?php echo $instance['bodyColor']; ?>" class="wd-color-field widefat" data-default-color="<?php echo $instance['bodyColor']; ?>" />
		</p>
		
		<?php /* Twitter.com code block */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_widget_code' ); ?>">
				<?php _e('Twitter.com widget code (optional):', $this->textDomain); ?>
				<a href="#" class="tooltip" data-tip="Paste the widget code from twitter.com here. This will override the original widget">(?)</a>
			</label>
			<textarea dir="ltr" id="<?php echo $this->get_field_id( 'twitter_widget_code' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_code' ); ?>" class="widefat" ><?php echo $instance['twitter_widget_code']; ?></textarea>
		</p>
				
		</div>
				
	<?php

	} // End form

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
		delete_option( 'twitterFeedCache' );
		delete_option( 'twitterFeedCacheTime' );	
		delete_option( 'twitterFeedTokens' );	
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		
		// Color picker style
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->widgetName.'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
			
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {

		// Color picker script (and admin script)
    	wp_enqueue_script( 'wp-color-picker' );
    	wp_enqueue_script( $this->widgetName.'-admin-script', plugins_url('js/admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    	
    	$options = array('url' => plugins_url( 'username-validation.php', __FILE__ ) );
    	wp_localize_script( $this->widgetName.'-admin-script', $this->cssClass.'_options', $options );
				
	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		wp_enqueue_style( $this->widgetName.'-core', plugins_url( 'css/core.css', __FILE__ ) );
		wp_enqueue_style( $this->widgetName.'-default-skin', plugins_url( 'css/default-skin.css', __FILE__ ) );

	} // end register_widget_styles
	
	
	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {
	
		// Load jQuery
		wp_enqueue_script('jquery');
		wp_enqueue_script( $this->widgetName.'-loader', plugins_url('js/loader.js', __FILE__ ), array( 'jquery' ), false, true );
		wp_enqueue_script( $this->widgetName.'-script', plugins_url('js/script.js', __FILE__ ), array( 'jquery' ), false, true );
		
	} // end register_widget_scripts

} // end class

// Register widget
add_action( 'widgets_init', create_function( '', 'register_widget("WDTwitterFeed");' ) ); 

