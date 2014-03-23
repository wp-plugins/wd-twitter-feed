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
	private $options;	private static $instance = null;	private $enable_caching;	private $last_caching_time;	private $elapsed_time;	private $caching_frequency;	private $request_made = false;	private $cache;	private $debug;	private $twitter_feed_options;
	
	/**
	 * Constructor
	 */
	private function __construct() {		if(is_admin())
			return;		$this->cache = Cache::get_instance();		$this->twitter_feed_options = TwitterFeedOptions::get_instance();
		$this->options = get_option( $this->twitter_feed_options->get_option_name() );		$this->tokens = array(
			'oauth_access_token' => $this->options['oauth_access_token'],
			'oauth_access_token_secret' => $this->options['oauth_access_token_secret'],
			'consumer_key' => $this->options['consumer_key'],
			'consumer_secret' => $this->options['consumer_secret']
		);		if($this->options['enable_caching']) {			$this->enable_caching = true;
			$this->caching_frequency = $this->options['caching_freq'];
			$this->last_caching_time = $this->options['last_caching_time'];
			$this->elapsed_time = time() - $this->last_caching_time;
			$this->cache->update_cache_data( $this->options['cache_data'] );			if($this->elapsed_time > $this->caching_frequency)				$this->cache->clear_cache_data();		}		add_action('shutdown', array($this, 'shutdown'));		$this->debug = Debug::get_instance();
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
	public function shutdown() {		if($this->request_made) {
			$this->debug->append('updating time and cache in database');
			$this->options['last_caching_time'] = time();			$this->options['cache_data'] = $this->cache->get_cache_data();
			update_option( $this->twitter_feed_options->get_option_name(), $this->options);
		}		if($this->options['debug_mode'])
			echo $this->debug->get_data_as_html();
	}
	
	/**
	 * Return an instance of this class
	 * 
	 * @return TweetsParser The only instance of this class
	 */
	public static function get_instance() {		if ( null == self::$instance ) {
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
	public function getTweets(array $settings) {		if(($settings['resource'] == 'usertimeline' || $settings['resource'] == 'list') && !isset($settings['user']))
			throw new \Exception('No username was provided');		if($settings['resource'] == 'search' && !isset($settings['query']))
			throw new \Exception('You must provide a search query when using "search" as a twitter resource');		if($settings['resource'] == 'list' && !isset($settings['list']))
			throw new \Exception('You must provide a list name when using "list" as a twitter resource');		if(	!$this->tokens['oauth_access_token'] || 
			!$this->tokens['oauth_access_token_secret'] ||
			!$this->tokens['consumer_key'] ||
			!$this->tokens['consumer_secret'])
			throw new \Exception('You did not provide the required Twitter tokens');		$resp = $this->fetchFeed($settings);
		
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
	private function fetchFeed(array $settings) {		$resource_class = __NAMESPACE__.'\\Twitter_'.$settings['resource'];
		$resource = new $resource_class($settings);		$this->debug->append('Resource: ' . $settings['resource']);		if($this->enable_caching) {
			$this->debug->append( 'Caching is enabled' );			if(!$resource->is_in_cache($settings['count'])) {				$this->debug->append( 'Fetching new data' );				$tweets = $this->toTweetArray($resource->perform_request($this->tokens));
				$resource->update_cache_data($tweets);				$this->request_made = true;
			}			$tweets = $resource->get_cached_data($settings['count']);
		}		else {
			$this->debug->append( 'Caching not enabled' );
			$tweets = $this->toTweetArray($resource->perform_request($this->tokens));
		}		$tweets = array_slice($tweets, 0, $settings['count']);		return $tweets;
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
	private function toTweetArray($data) {		if($data->errors) {
			$message = "";
			foreach($data->errors as $err)
				$message .= $err->message . " (error code: " . $err->code . ")\n";
			
			throw new \Exception($message);
		}		$tweets = array();
		$this->debug->append('crude tweets = ' . print_r($tweets, true));		foreach ($data as $key) {			if($key->retweeted_status) {
				$retweeter = $key->user->name;				$key = $key->retweeted_status;
			} else $retweeter = null;			$tweet = new Tweet(array(
				'created_at' => $key->created_at, 
				'image_url' => $key->user->profile_image_url, 
				'screen_name' => $key->user->screen_name, 
				'user_name' => $key->user->name, 
				'tweet_text' => $key->text, 
				'id_str' => $key->id_str,
				'retweeter' => $retweeter,
				'retweet_count' => $key->retweet_count,
				'favorite_count' => $key->favorite_count
			));			$tweets[] = $tweet;
		}

		return $tweets;
	}
}
