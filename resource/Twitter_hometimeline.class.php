<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

class Twitter_hometimeline extends TwitterResource {
	
	public function init() {}
	
	public function get_cached_data() {
		$cache_data = self::$cache->get_cache_data();
		return $cache_data['hometimeline'];
	}

	public function is_in_cache() {
		$cache_data = self::$cache->get_cache_data();
		return (isset($cache_data['hometimeline']) && count($cache_data['hometimeline']) >= $this->settings['count']);
	}

	public function update_cache_data($tweets) {
		$cache_data = self::$cache->get_cache_data();
		$cache_data['hometimeline'] = $tweets;
		self::$cache->update_cache_data($cache_data);
	}

	public function build_argument_list() {		$args = array();
		if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
		if ( !$this->settings['replies'] ) $args['exclude_replies'] = 'true';
		if ( $this->settings['retweets'] ) $args['include_rts'] = 'true';
		return parent::build_argument_list($args);
	}

	public function get_url() {
		return self::API_URL . self::HOME_TIMELINE;
	}	
	
	public function filter_response($resp) {
		return $resp;
	}	
}