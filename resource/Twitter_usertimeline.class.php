<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

class Twitter_usertimeline extends TwitterResource {
	private $user;
	
	public function init() {
		$this->user = $this->settings['user'];
	}
	
	public function get_cached_data() {
		$cache_data = self::$cache->get_cache_data();
		return $cache_data['usertimeline'][$this->user];
	}

	public function is_in_cache() {
		$cache_data = self::$cache->get_cache_data();
		return (isset($cache_data['usertimeline'][$this->user]) && count($cache_data['usertimeline'][$this->user]) >= $this->settings['count']);
	}

	public function update_cache_data($tweets) {
		$cache_data = self::$cache->get_cache_data();
		$cache_data['usertimeline'][$this->user] = $tweets;
		self::$cache->update_cache_data($cache_data);
	}

	public function build_argument_list() {		$args = array();
		if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
		if ( !$this->settings['replies'] ) $args['exclude_replies'] = 'true';
		if ( $this->settings['retweets'] ) $args['include_rts'] = 'true';
		$args['screen_name'] = $this->settings['user'];
		return parent::build_argument_list($args);
	}

	public function get_url() {
		return self::API_URL . self::USER_TIMELINE;
	}

	public function filter_response($resp) {
		return $resp;
	}	
}