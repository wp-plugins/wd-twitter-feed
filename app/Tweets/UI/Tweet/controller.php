<?php

/**
 * @package   Twitter Feed
 * @date      Tue Feb 03 2015 18:42:58
 * @version   2.0.1
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Tweets\UI;

/**
 * Implements a single tweet controller. Used by tweet lists objects.
 */
class Tweet extends \Amarkal\Template\Controller
{
    protected $tweet;
    
    protected $params;
    
    public function __construct( $tweet, $params )
    {
        $this->tweet  = $tweet;
        $this->params = $params;
        $this->intent = 'https://twitter.com/intent/';
    }
    
    /**
     * Get the path to the template (script).
     * @return string    The path.
     */
    public function get_script_path() 
    {
        return dirname( __FILE__ ) . '/template.phtml';
    }
}
