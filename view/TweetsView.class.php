<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*--------------------------------------------------*\
 * Tweets View class
 * 
 * This abstract class is the parent class to every
 * tweet view class. 
\*--------------------------------------------------*/

abstract class TweetsView {
	
	protected $tweets;
	protected $options;
	
	/**
	 * Constructor
	 * 
	 * @param	Tweet[]	$t	The tweet list
	 * @param	type $o		The options
	 */
	public function __construct($t, $o) {
		$this->setTweets($t);
		$this->setOptions($o);
	}
	
	/**
	 * Set the tweets list
	 * @param type $tweets the tweets to set
	 */
	public function setTweets($tweets) {
		$this->tweets = $tweets;
	}
	
	/**
	 * Set Options
	 * @param type $options the options to set
	 */
	public function setOptions($options) {
		$this->options = $options;
	}
	
	/**
	 * Render the tweets as HTML
	 */
	public abstract function render();
	
	/**
	 * Get Tweet Text
	 * 
	 * 
	 * 
	 * @param \TWITTERFEED\Tweet $tweet
	 * @return string	The reformatted tweet text
	 */
	protected function getTweetText(Tweet $tweet) {
		return $this->linkifyTweets(utf8_decode($tweet->getTweetText()), true);
	}
	
	/*--------------------------------------------------*/
	/* Linkify Tweets
	/* Create hyperlinks from text
	/*--------------------------------------------------*/
	protected function linkifyTweets($tweet, $blank = false) {
		// Open links in a new window
		$blank = $blank ? 'target="_blank"' : '';
		
		// Linkify URLs (hide http:// prefix)
		$tweet = preg_replace(
			'/(https?:\/\/(\S+))/',
			'<a href="\1" class="preg-links" '.$blank.'>\2</a>',
			$tweet
		);
		
		// Linkify twitter users
		$tweet = preg_replace(
			'/(^|\s)@(\w+)/',
			'\1@<a href="http://twitter.com/\2" class="preg-links" '.$blank.'>\2</a>',
			$tweet
		);
		
		// Linkify tags
		$tweet = preg_replace(
			'/(^|\s)#(\w+)/',
			'\1<a href="http://twitter.com/search?q=%23\2" class="preg-links" '.$blank.'>#\2</a>',
			$tweet
		);
 
		return $tweet;
	}
}	