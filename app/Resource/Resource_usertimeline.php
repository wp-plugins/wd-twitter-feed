<?php

/**
 * @package   Twitter Feed
 * @date      Thu Apr 02 2015 00:53:18
 * @version   2.0.4
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Resource;

class Resource_usertimeline extends TwitterResource 
{
    private $user;
    
    public function init()
    {
        $this->user = $this->settings['user'];
    }
    
    public function get_cached_data() 
    {
        $cache_data = self::$cache->data();
        return $cache_data['usertimeline'][$this->user];
    }

    public function is_in_cache() 
    {
        $cache_data = self::$cache->data();
        return (isset($cache_data['usertimeline'][$this->user]) && count($cache_data['usertimeline'][$this->user]) >= $this->settings['count']);
    }

    public function update_cache_data($tweets) 
    {
        $cache_data = self::$cache->data();
        $cache_data['usertimeline'][$this->user] = $tweets;
        self::$cache->update($cache_data);
    }

    public function build_argument_list( array $args = array() ) 
    {
        // Build the argument list
        if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
        if ( isset($this->settings['replies']) ) $args['exclude_replies'] = $this->settings['replies'] ? 'false' : 'true';
        if ( isset($this->settings['retweets']) ) $args['include_rts'] = $this->settings['retweets'] ? 'true' : 'false';
        $args['screen_name'] = $this->settings['user'];
        return parent::build_argument_list($args);
    }

    public function get_url() 
    {
        return self::API_URL . self::USER_TIMELINE;
    }

    public function filter_response($resp) 
    {
        return $resp;
    }
}