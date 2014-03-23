<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

class Twitter_retweetsofme extends TwitterResource {
	
	public function init() {}
	
	public function get_cached_data() {
		$cache_data = self::$cache->get_cache_data();
		return $cache_data['retweetsofme'];
	}

	public function is_in_cache() {
		$cache_data = self::$cache->get_cache_data();
		return (isset($cache_data['retweetsofme']) && count($cache_data['retweetsofme']) >= $this->settings['count']);
	}

	public function update_cache_data($tweets) {
		$cache_data = self::$cache->get_cache_data();
		$cache_data['retweetsofme'] = $tweets;
		self::$cache->update_cache_data($cache_data);
	}

	public function build_argument_list() {		$args = array();
		if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
		if ( !$this->settings['replies'] ) $args['exclude_replies'] = 'true';
		$args['include_rts'] = 'true'; // Must inclide retweets
		return parent::build_argument_list($args);
	}

	public function get_url() {
		return self::API_URL . self::RETWEETS_OF_ME;
	}
	
	public function filter_response($resp) {
		return $resp;
	}	
}
