<?php

/**
 * @package   Twitter Feed
 * @date      Mon Apr 27 2015 18:06:42
 * @version   2.0.5
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Parser;

/**
 * Cache
 * 
 * The cache acts as a storage place for all the tweets on the local machine.
 * This class implements the singleton desing pattern, so there is only
 * one instance of Cache at any given time. 
 */
class Cache 
{    
    private static $instance = null;     // Only instance
    private $option_name = 'tf_cache';
    private $cache;                      // The cache data and time is stored here
    
    /**
     * Return an instance of this class
     * 
     * @return TweetsParser The only instance of this class
     */
    public static function get_instance() 
    {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    private function __construct()
    {
        // Fetch the data from the db
        $this->cache = get_option( $this->option_name );
    }
    
    public function update( $new_data )
    {
        $this->cache['data'] = $new_data;
        $this->cache['time'] = time();
        update_option( $this->option_name, $this->cache );
    }
    
    public function data()
    {
        return $this->cache['data'];
    }
    
    public function clear()
    {
        $this->cache['data'] = array();
        update_option($this->option_name, $this->cache);
    }
    
    public function elapsed()
    {
        return time() - $this->cache['time'];
    }
}
