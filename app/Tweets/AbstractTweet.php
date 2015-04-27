<?php

/**
 * @package   Twitter Feed
 * @date      Mon Apr 27 2015 18:06:42
 * @version   2.0.5
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Tweets;

/**
 * Abstract tweet
 * 
 * Parent class to all other Twitter Feed UI elements
 */
abstract class AbstractTweet extends \Amarkal\Template\Controller
{
    protected $tweets;
    protected $settings;
    protected $template;

    /**
     * Constructor
     * 
     * @param Tweet[] $tweets The tweet list
     * @param array $settings The options
     */
    public function __construct( $tweets, $settings )
    {
        $this->tweets   = $tweets;
        $this->direction= \is_rtl() ? 'rtl' : 'ltr';
        $this->settings = array_merge( $this->get_defaults(), $settings );
    }
    
    /**
     * Get the default options for this tweet view
     */
    public abstract function get_defaults();
    
    /**
     * Get the path to the template (script).
     * @return string    The path.
     */
    public function get_script_path() 
    {
        $class_name =  substr( get_called_class() , strrpos( get_called_class(), '\\') + 1);
        return dirname( __FILE__ ) . '/UI/' . $class_name . '/template.phtml';
    }
    
    /**
     * Convert tweet text to an HTML representation of it, including 
     * links, hash tags etc...
     * 
     * @param string $text
     * @return string The reformatted tweet text
     */
    static function format_tweet_text( $text )
    {
        return self::linkify_tweets( utf8_decode( $text ), true );
    }
    
    /**
     * Linkify Tweets
     * Create hyperlinks from text
     */
    static function linkify_tweets( $tweet_text, $blank = false )
    {
        // Open links in a new window
        $blank = $blank ? 'target="_blank"' : '';
        
        // Linkify URLs (hide http:// prefix)
        $tweet_text = preg_replace(
            '/(https?:\/\/(\S+))/',
            '<a href="\1" class="preg-links" '.$blank.'>\2</a>',
            $tweet_text
        );
        
        // Linkify twitter users
        $tweet_text = preg_replace(
            '/(^|\s)@(\w+)/',
            '\1@<a href="http://twitter.com/\2" class="preg-links" '.$blank.'>\2</a>',
            $tweet_text
        );
        
        // Linkify tags
        $tweet_text = preg_replace(
            '/(^|\s)#(\w+)/',
            '\1<a href="http://twitter.com/search?q=%23\2" class="preg-links" '.$blank.'>#\2</a>',
            $tweet_text
        );
 
        return $tweet_text;
    }
    
    public function __get($name) 
    {
        if( isset($this->settings[$name]) )
        {
            return $this->settings[$name];
        }
    }
}