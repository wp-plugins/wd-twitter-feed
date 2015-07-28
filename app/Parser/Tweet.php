<?php
/**
 * @package    twitterfeed
 * @date       Tue Jul 28 2015 14:02:04
 * @version    2.0.8
 * @author     Askupa Software <contact@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2014 Askupa Software
 */

namespace TwitterFeed\Parser;

/**
 * Tweet
 * 
 * The Tweet object is used throughout the plugin as a means to store and 
 * retrieve tweet data
 */
class Tweet 
{
    private $params;
    
    public function __construct( array $params ) 
    {
        $this->params = $params;
    }
    
    public function __get($name) 
    {
        return $this->params[$name];
    }
}