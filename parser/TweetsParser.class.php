<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * Tweets Parser
 * 
 * This class handles all the requests to Twitter as well as the caching and 
 * handling of the data retrieved from Twitter. This class follows the singleton 
 * design pattern.
 * 
 * About the caching algorithm:
 * This is a lazy algorithm. It only makes a request if one is needed, and it 
 * gets the minimum number of tweets that satisfy the user's demands.
 * When a page is loaded, this algorithm checks to see if the time since the 
 * last Twitter request is exceeding the time specified by the user. If it does, 
 * a request to Twitter will be made. If there is more than one TweetsView
 * object within a page, the algorithm will check to see if another request is
 * needed by first checking the cache data to see if the data is already 
 * available there.
\*----------------------------------------------------------------------------*/

class TweetsParser {
	
	private $tokens;
	private $options;
	
	// Only instance
	private static $instance = null;
	
	// Caching variables
	private $enable_caching;				// True if the user has enabled caching
	private $last_caching_time;				// The time of the last twitter request (stored in the db)
	private $elapsed_time;					// The time passed from the last request
	private $caching_frequency;				// The user specified threshold to make a new request
	private $request_made = false;			// True if a request has been made during this run
	private $cache;							// The cache data is stored here
	
	// Debug
	private $debug;
	
	// Options framework
	private $twitter_feed_options;
	
	/**
	 * Constructor
	 */
	private function __construct() {
		
		// Bail early if this is the administration panel
		if(is_admin())
			return;
		
		// Get the cache instance
		$this->cache = Cache::get_instance();	
		
		// Set the options
		$this->twitter_feed_options = TwitterFeedOptions::get_instance();
		$this->options = get_option( $this->twitter_feed_options->get_option_name() );
		
		// Set the tokens
		$this->tokens = array(
			'oauth_access_token' => $this->options['oauth_access_token'],
			'oauth_access_token_secret' => $this->options['oauth_access_token_secret'],
			'consumer_key' => $this->options['consumer_key'],
			'consumer_secret' => $this->options['consumer_secret']
		);

		// Set the caching variables
		if($this->options['enable_caching']) {
			
			// Set the local variables
			$this->enable_caching = true;
			$this->caching_frequency = $this->options['caching_freq'];
			$this->last_caching_time = $this->options['last_caching_time'];
			$this->elapsed_time = time() - $this->last_caching_time;
			$this->cache->update_cache_data( $this->options['cache_data'] );
			
			// Elapsed time from last call is greater 
			// than the specified caching frequency			
			if($this->elapsed_time > $this->caching_frequency)
				// Clear the cache
				$this->cache->clear_cache_data();
				//$this->cache = array();
		}
		
		// Hook the shutdown action
		add_action('shutdown', array($this, 'shutdown'));
		
		// Debug
		$this->debug = Debug::get_instance();
		$this->debug->append('Elapsed time: ' . $this->elapsed_time);
		$this->debug->append('Caching Frequency: ' . $this->caching_frequency);
	}
	
	/**
	 * Shutdown function
	 * 
	 * Called when PHP is about to end execution.
	 * Handles the updating of the cache data and
	 * time
	 */
	public function shutdown() {
		// A request was made to fetch new data from twitter (this happens
		// when the elapsed time from the last fetch is greater than the
		// specified caching frequency). Therefore, we need to update the 
		// last caching time and caching data
		if($this->request_made) {
			$this->debug->append('updating time and cache in database');
			$this->options['last_caching_time'] = time();
			
			// Update the options
			//$this->options['cache_data'] = $this->cache;
			$this->options['cache_data'] = $this->cache->get_cache_data();
			update_option( $this->twitter_feed_options->get_option_name(), $this->options);
		}
		
		// Display a window with all debug data if debug mode is enabled
		if($this->options['debug_mode'])
			echo $this->debug->get_data_as_html();
	}
	
	/**
	 * Return an instance of this class
	 * 
	 * @return TweetsParser The only instance of this class
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/**
	 * Get the Tweets, either from Twitter.com
	 * or from the cached data, if exits.
	 * 
	 * @return Tweets[] tweets array
	 */
	public function getTweets(array $settings) {
				
		// No user was specified - bail early and throw an error
		if(($settings['resource'] == 'usertimeline' || $settings['resource'] == 'list') && !isset($settings['user']))
			throw new \Exception('No username was provided');
		
		// Must provide a search query for 'search' type resource
		if($settings['resource'] == 'search' && !isset($settings['query']))
			throw new \Exception('You must provide a search query when using "search" as a twitter resource');
		
		// Must provide a list name for 'list' type resource
		if($settings['resource'] == 'list' && !isset($settings['list']))
			throw new \Exception('You must provide a list name when using "list" as a twitter resource');
		
		// No credentials were provided
		if(	!$this->tokens['oauth_access_token'] || 
			!$this->tokens['oauth_access_token_secret'] ||
			!$this->tokens['consumer_key'] ||
			!$this->tokens['consumer_secret'])
			throw new \Exception('You did not provide the required Twitter tokens');
		
		// Fetch the feed
		$resp = $this->fetchFeed($settings);
		
		return $resp;
	}
	
	/**
	 * Fetch Feed
	 * 
	 * Fetches the feed and handles the errors. If the user 
	 * has specified caching freuency, the function would 
	 * claculate the elapsed time from last fetch and would 
	 * use the last cached feed if the elapsed time is smaller 
	 * than the specified chaching frequency. Otherwise, the 
	 * function will make a request to Twitter.com to fetch 
	 * the live data.
	 * 
	 * @return Tweets[] tweets array
	 */
	private function fetchFeed(array $settings) {
		
		// Create a new twitter resource
		$resource_class = __NAMESPACE__.'\\Twitter_'.$settings['resource'];
		$resource = new $resource_class($settings);

		// Debug
		$this->debug->append('Resource: ' . $settings['resource']);
		
		//-------------------
		// Caching is enabled
		//-------------------
		
		if($this->enable_caching) {
			$this->debug->append( 'Caching is enabled' );
			
			// Resource is not in cache
			if(!$resource->is_in_cache($settings['count'])) {
				
				// Debug
				$this->debug->append( 'Fetching new data' );
								
				// Fetch new data
				$tweets = $this->toTweetArray($resource->perform_request($this->tokens));
				$resource->update_cache_data($tweets);

				// Notify that a request was made
				$this->request_made = true;
			}
			
			// Set the tweets
			$tweets = $resource->get_cached_data($settings['count']);
		}
		
		//--------------------
		// Caching not enabled
		//--------------------
		
		// Make a new request
		else {
			$this->debug->append( 'Caching not enabled' );
			$tweets = $this->toTweetArray($resource->perform_request($this->tokens));
		}

		// Get only the requested number of tweets
		$tweets = array_slice($tweets, 0, $settings['count']);
	
		// Return the Tweet[] array
		return $tweets;
	}
	
	/**
	 * Convert Twitter 1.1 API data to an array of
	 * Tweet objects
	 * 
	 * @param	array	$data	the data fetched from
	 *							Twitter.com
	 * @throws	Exception		if the request returned errors
	 * @return	Tweet			the Tweet array
	 */
	private function toTweetArray($data) {
		// Check for errors
		if($data->errors) {
			$message = "";
			foreach($data->errors as $err)
				$message .= $err->message . " (error code: " . $err->code . ")\n";
			
			throw new \Exception($message);
		}

		// Tweet[] array
		$tweets = array();
		$this->debug->append('crude tweets = ' . print_r($tweets, true));
		
		// Each tweet in the array
		foreach ($data as $key) {
			
			// Take retweeted info if this is a retweet
			if($key->retweeted_status) {
				
				// Get retweeter user name
				$retweeter = $key->user->name;
				
				// Replace tweet with retweeted status
				$key = $key->retweeted_status;
			
				
			} else $retweeter = null;
			
			// Media
			$entities = $key->entities;
			if(isset($entities)) {
				$media = array();
				
				// Images
				if(isset($entities->media))
					foreach($entities->media as $_media) {
						$media[] = array(
							'type' => $_media->type,
							'url' => $_media->media_url,
							'width' => $_media->sizes->large->w,
							'height' => $_media->sizes->large->h
						);
					}
				
				// Vine & YouTube
				if(isset($entities->urls))
					foreach($entities->urls as $url) {
						
						// Vine
						if(strpos($url->display_url, 'vine.co') === 0)
							$media[] = array(
								'type' => 'vine',
								'url' => $url->expanded_url,
								'embed_url' => $url->expanded_url.'/card'
							);
						
						// YouTube
						if(strpos($url->display_url, 'youtu.be') === 0)
							$media[] = array(
								'type' => 'youtube',
								'url' => $url->expanded_url,
								'embed_url' => 'http://www.youtube.com/embed/'.str_replace('http://youtu.be/', '', $url->expanded_url)
							);
					}
			}
			
			// Create a new Tweet object
			$tweet = new Tweet(array(
				'created_at' => $key->created_at, 
				'image_url' => $key->user->profile_image_url, 
				'screen_name' => $key->user->screen_name, 
				'user_name' => $key->user->name, 
				'tweet_text' => utf8_encode($key->text), // Tweet text may contain special characters
				'id_str' => $key->id_str,
				'retweeter' => $retweeter,
				'retweet_count' => $key->retweet_count,
				'favorite_count' => $key->favorite_count,
				'media' => $media == array() ? null : $media
			));
			
			// Add it to the collection
			$tweets[] = $tweet;
		}

		return $tweets;
	}
}
