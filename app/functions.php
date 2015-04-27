<?php

/**
 * @package   Twitter Feed
 * @date      Mon Apr 27 2015 18:06:42
 * @version   2.0.5
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed;

/**
 * The following functions are used as the application programming interface (API)
 * of Twitter Feed. Function names that are prepended with an underscore (_)
 * represent system functions that are only to be used internally.
 */

/**
 * Returns the value of $yes if this is the full version
 * Returns the value of $no otherwise
 * 
 * @ignore
 * @since    1.3
 * 
 * @param    $yes    The object to return if this is the full version
 * @param    $no     The object to return if this is the demo version
 */
function _is_full_version( $yes, $no ) 
{
    return ( 'Full' === PLUGIN_VERSION_TYPE ? $yes : $no );
}

/**
 * Render an error message
 * 
 * @ignore
 * @since    1.43
 * 
 * @param    string    $message    The error message
 * @return   The HTML formatted error message    
 */
function _render_error( $message ) 
{
    ob_start();
    include( dirname( __FILE__ ) . '/Tweets/Error.phtml' );
    return ob_get_clean();
}

/**
 * Return an HTML view of static tweets.
 * 
 * This function makes a call to **Twitter REST API** and returns an HTML
 * formatted representation of the data retruned. The data is displayed
 * as **static tweets**.
 * 
 * Parameters:
 * <ul>
 * <li><b>user</b> <i>string</i> The user name.</li>
 * <li><b>count</b> <i>string</i> The number of tweets.</li>
 * <li><b>replies</b> <i>boolean</i> True/false whether to include replies.</li>
 * <li><b>retweets</b> <i>boolean</i> True/false whether to include retweets.</li>
 * <li><b>resource</b> <i>string</i> The type of twitter resource.</li>
 * <li><b>list</b> <i>string</i> The name of the list, if the resource was specified as a twitter list.</li>
 * <li><b>query</b> <i>string</i> The search query, if the resource was specified as a twitter search.</li>
 * <li><b>skin</b> <i>string</i> The tweet skin.</li>
 * <li><b>assets</b> <i>array</i> List of tweet assets to show. Available assets are:
 * <ul>
 * <li>username</li>
 * <li>screenname</li>
 * <li>avatar</li>
 * <li>time</li>
 * <li>actions</li>
 * <li>media</li>
 * </ul>
 * </li>
 * </ul>
 * 
 * @api
 * @since 1.43
 * @return string The HTML formatted tweets.
 */
function static_tweets( $options ) 
{
    $parser = Parser\TweetsParser::get_instance();

    // Get the tweets
    try {
        $tweets = $parser->getTweets($options);
    }
    catch (\Exception $e) 
    {
        return _render_error( $e->getMessage() );
    }
    
    // Create a new tweet view
    $tweetview = new Tweets\UI\StaticTweets( $tweets, $options );

    // Get the html formatted list
    return $tweetview->render();
}

/**
 * Return an HTML view of scrolling tweets.
 * 
 * This function makes a call to Twitter REST API and returns an HTML
 * formatted representation of the data retruned. The data is displayed
 * as scrolling tweets.
 *
 * Parameters:
 * <ul>
 * <li><b>user</b> <i>string</i> The user name.</li>
 * <li><b>count</b> <i>string</i> The number of tweets.</li>
 * <li><b>replies</b> <i>boolean</i> True/false whether to include replies.</li>
 * <li><b>retweets</b> <i>boolean</i> True/false whether to include retweets.</li>
 * <li><b>resource</b> <i>string</i> The type of twitter resource.</li>
 * <li><b>list</b> <i>string</i> The name of the list, if the resource was specified as a twitter list.</li>
 * <li><b>query</b> <i>string</i> The search query, if the resource was specified as a twitter search.</li>
 * <li><b>skin</b> <i>string</i> The tweet skin.</li>
 * <li><b>scroll_time</b> <i>number</i> The duration of each scroll.</li>
 * </ul>
 * 
 * @api
 * @since 1.43
 * @return        string    The HTML formatted tweets.
 */
function scrolling_tweets( $options ) 
{
    $parser = Parser\TweetsParser::get_instance();

    // Get the tweets
    try {
        $tweets = $parser->getTweets($options);
    } 
    catch (\Exception $e) 
    {
        return _render_error( $e->getMessage() );
    }
    
    // Create a new tweet view
    $tweetview = new Tweets\UI\ScrollingTweets( $tweets, $options );

    // Get the html formatted list
    return $tweetview->render();
}

/**
 * 
 * Return an HTML view of sliding tweets.
 * 
 * This function makes a call to Twitter REST API and returns an HTML
 * formatted representation of the data retruned. The data is displayed
 * as sliding tweets.
 *
 * Parameters:
 * <ul>
 * <li><b>user</b> <i>string</i> The user name.</li>
 * <li><b>count</b> <i>string</i> The number of tweets.</li>
 * <li><b>replies</b> <i>boolean</i> True/false whether to include replies.</li>
 * <li><b>retweets</b> <i>boolean</i> True/false whether to include retweets.</li>
 * <li><b>resource</b> <i>string</i> The type of twitter resource.</li>
 * <li><b>list</b> <i>string</i> The name of the list, if the resource was specified as a twitter list.</li>
 * <li><b>query</b> <i>string</i> The search query, if the resource was specified as a twitter search.</li>
 * <li><b>skin</b> <i>string</i> The tweet skin.</li>
 * <li><b>slide_dir</b> <i>string</i> The slide direction. One of [up|down|left|right|random]</li>
 * <li><b>slide_duration</b> <i>number</i> The slide duration (in seconds).</li>
 * <li><b>assets</b> <i>array</i> List of tweet assets to show. Available assets are:
 * <ul>
 * <li>username</li>
 * <li>screenname</li>
 * <li>avatar</li>
 * <li>time</li>
 * <li>actions</li>
 * </ul>
 * </li>
 * </ul>
 * 
 * @api
 * @since 1.43
 * @return        string    The HTML formatted tweets.
 */
function sliding_tweets( $options ) 
{
    $parser = Parser\TweetsParser::get_instance();

    // Get the tweets
    try 
    {
        $tweets = $parser->getTweets($options);
    } 
    catch (\Exception $e) 
    {
        return _render_error( $e->getMessage() );
    }
    
    // Create a new tweet view
    $tweetview = new Tweets\UI\SlidingTweets( $tweets, $options );

    // Get the html formatted list
    return $tweetview->render();
}