<?php
/**
 * This functions are used as the application programming interface (API)
 * of Twitter Feed. Function names that are prepended with an underscore (_)
 * represent system functions that are only used internally.
 * 
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/**
 * Returns the value of $yes if this is the full version
 * Returns the value of $no otherwise
 * 
 * @ignore
 * @since	1.3
 * 
 * @param	$yes	The object to return if this is the full version
 * @param	$no		The object to return if this is the demo version
 */
function _is_full_version( $yes, $no ) {
	return ( 'Full' === PLUGIN_VERSION_TYPE ? $yes : $no );
}

/**
 * Render an error message
 * 
 * @ignore
 * @since	1.43
 * 
 * @param	string	$message	The error message
 * @return	The HTML formatted error message	
 */
function _render_error( $message ) {
	ob_start();
	include( dirname( __DIR__ ) . '/view/ErrorView.php' );
	return ob_get_clean();
}

/**
 * 
 * Return an HTML view of static tweets.
 * 
 * This function makes a call to **Twitter REST API** and returns an HTML
 * formatted representation of the data retruned. The data is displayed
 * as **static tweets**.
 *
 * @api
 * @since 1.43
 * 
 * @param		mixed[]	$options {
 *		@type	string	$user			The user name.
 *		@type	string	$count			The number of tweets.
 *		@type	string	$resource		The type of twitter resource.
 *		@type	string	$list			The name of the list, if the resource
 *										was specified as a twitter list.
 *		@type	string	$query			The search query, if the resource was
 *										specified as a twitter search.
 *		@type	string	$dir			The directory of the tweet (left/right).
 *		@type	string	$skin			The tweet skin.
 * }
 * @param		mixed[]	$assets {
 *		@type	string	$username		True/false show user name.
 *		@type	string	$screenname		True/false show screen name.
 *		@type	string	$avatar			True/false show avatar.
 *		@type	string	$tweettime		True/false show tweet time.
 *		@type	string	$tweetactions	True/false show tweet actions.
 *		@type	string	$replies		True/false to show replies.
 *		@type	string	$retweets		True/false to show retweets.
 *		@type	string	$media			True/false to show retweets.
 *		
 * }
 * 
 * @return		string	The HTML formatted tweets.
 */
function static_tweets( $options, $assets ) {

	$parser = TweetsParser::get_instance();

	// Get the tweets
	try {
		$tweets = $parser->getTweets(array(
			'user'		=> $options['user'],
			'count'		=> $options['count'],
			'replies'	=> $assets['replies'],
			'retweets'	=> $assets['retweets'],
			'resource'	=> $options['resource'],
			'list'		=> $options['list'],
			'query'		=> $options['query']
		));
	} catch (\Exception $e) {
		return _render_error( $e->getMessage() );
	}
	
	// Create a new tweet view
	$tweetview = new StaticTweetsView( $tweets, array_merge( $options, $assets ) );

	// Get the html formatted list
	return $tweetview->render();
}

/**
 * 
 * Return an HTML view of scrolling tweets.
 * 
 * This function makes a call to Twitter REST API and returns an HTML
 * formatted representation of the data retruned. The data is displayed
 * as scrolling tweets.
 *
 * @api
 * @since 1.43
 * 
 * @param		mixed[]	$options {
 *		@type	string	$user			The user name.
 *		@type	string	$count			The number of tweets.
 *		@type	string	$resource		The type of twitter resource.
 *		@type	string	$list			The name of the list, if the resource
 *										was specified as a twitter list.
 *		@type	string	$query			The search query, if the resource was
 *										specified as a twitter search.
 *		@type	string	$dir			The directory of the tweet (left/right).
 *		@type	string	$skin			The tweet skin.
 *		@type	string	$scroll_time	The duration of each scroll.
 * }
 * 
 * @return		string	The HTML formatted tweets.
 */
function scrolling_tweets( $options ) {

	$parser = TweetsParser::get_instance();

	// Get the tweets
	try {
		$tweets = $parser->getTweets(array(
			'user'		=> $options['user'],
			'count'		=> $options['count'],
			'replies'	=> false,
			'retweets'	=> false,
			'resource'	=> $options['resource'],
			'list'		=> $options['list'],
			'query'		=> $options['query']
		));
	} catch (\Exception $e) {
		return _render_error( $e->getMessage() );
	}
	
	// Create a new tweet view
	$tweetview = new ScrollingTweetsView( $tweets, $options );

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
 * @api
 * @since 1.43
 * 
 * @param		mixed[]	$options {
 *		@type	string	$user			The user name.
 *		@type	string	$count			The number of tweets.
 *		@type	string	$resource		The type of twitter resource.
 *		@type	string	$list			The name of the list, if the resource
 *										was specified as a twitter list.
 *		@type	string	$query			The search query, if the resource was
 *										specified as a twitter search.
 *		@type	string	$dir			The directory of the tweet (left/right).
 *		@type	string	$skin			The tweet skin.
 *		@type	string	$slide_dir		The slide direction.
 *		@type	string	$slide_duration	The slide duration (in seconds).
 * }
 * @param		mixed[]	$assets {
 *		@type	string	$username		True/false show user name.
 *		@type	string	$screenname		True/false show screen name.
 *		@type	string	$avatar			True/false show avatar.
 *		@type	string	$tweettime		True/false show tweet time.
 *		@type	string	$tweetactions	True/false show tweet actions.
 *		@type	string	$replies		True/false to show replies.
 *		@type	string	$retweets		True/false to show retweets.
 * }
 * 
 * @return		string	The HTML formatted tweets.
 */
function sliding_tweets( $options, $assets ) {

	$parser = TweetsParser::get_instance();

	// Get the tweets
	try {
		$tweets = $parser->getTweets(array(
			'user'		=> $options['user'],
			'count'		=> $options['count'],
			'replies'	=> $assets['replies'],
			'retweets'	=> $assets['retweets'],
			'resource'	=> $options['resource'],
			'list'		=> $options['list'],
			'query'		=> $options['query']
		));
	} catch (\Exception $e) {
		return _render_error( $e->getMessage() );
	}
	
	// Create a new tweet view
	$tweetview = new SlidingTweetsView( $tweets, array_merge( $options, $assets ) );

	// Get the html formatted list
	return $tweetview->render();
}
