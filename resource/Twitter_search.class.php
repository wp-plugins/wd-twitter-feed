<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

class Twitter_search extends TwitterResource {
	
	private $query;
	
	public function init() {
		$this->query = $this->settings['query'];
	}
	
	public function get_cached_data() {
		$cache_data = self::$cache->get_cache_data();
		return $cache_data['search'][$this->query];
	}

	public function is_in_cache() {
		$cache_data = self::$cache->get_cache_data();
		return (isset($cache_data['search'][$this->query]) && count($cache_data['search'][$this->query]) >= $this->settings['count']);
	}

	public function update_cache_data($tweets) {
		$cache_data = self::$cache->get_cache_data();
		$cache_data['search'][$this->query] = $tweets;
		self::$cache->update_cache_data($cache_data);
	}

	public function build_argument_list() {		$args = array();
		if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
		$args['q'] = urlencode($this->settings['query']);
		return parent::build_argument_list($args);
	}

	public function get_url() {
		return self::API_URL . self::SEARCH;
	}	public function filter_response($resp) {
		return $resp->statuses;
	}
}