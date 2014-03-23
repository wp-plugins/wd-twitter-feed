<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/**
 * Cache
 * 
 * The cache acts as a storage place for all the tweets on the local machine.
 * This class implements the singleton desing pattern, so there is only
 * one instance of Cache at any given time. 
 */
class Cache {
	
	private static $instance = null;	private $cache;	
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
	
	public function update_cache_data($new_cache) {
		$this->cache = $new_cache;
	}
	
	public function get_cache_data() {
		return $this->cache;
	}
	
	public function clear_cache_data() {
		$this->cache = array();
	}
}
