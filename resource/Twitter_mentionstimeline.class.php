<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

class Twitter_mentionstimeline extends TwitterResource {
	
	public function init() {}
	
	public function get_cached_data() {
		$cache_data = self::$cache->get_cache_data();
		return $cache_data['mentionstimeline'];
	}

	public function is_in_cache() {
		$cache_data = self::$cache->get_cache_data();
		return (isset($cache_data['mentionstimeline']) && count($cache_data['mentionstimeline']) >= $this->settings['count']);
	}

	public function update_cache_data($tweets) {
		$cache_data = self::$cache->get_cache_data();
		$cache_data['mentionstimeline'] = $tweets;
		self::$cache->update_cache_data($cache_data);
	}

	public function build_argument_list() {		$args = array();
		if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
		if ( $this->settings['retweets'] ) $args['include_rts'] = 'true';
		$args['exclude_replies'] = 'false'; // Must include replies for mentions
		return parent::build_argument_list($args);
	}

	public function get_url() {
		return self::API_URL . self::MENTIONS_TIMELINE;
	}	
	
	public function filter_response($resp) {
		return $resp;
	}	
}