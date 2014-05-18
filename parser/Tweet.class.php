<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/** --------------------------------------------------------------------------*\
 * Tweet
 * 
 * The Tweet object is used throughout the plugin as a means to store and 
 * retrieve tweet data
\** --------------------------------------------------------------------------*/

class Tweet {
	private $date;
	private $image_url;
	private $screen_name;
	private $user_name;
	private $tweet_text;
	private $retweeter;
	private $tweet_id;
	private $retweet_count;
	private $favorite_count;
	private $media;
	
	/** ----------------------------------------------------------------------*\
	 * Constructor
	\** ----------------------------------------------------------------------*/
	public function __construct(array $arr) {
		$this->date				= $arr['created_at'];
		$this->image_url		= $arr['image_url'];
		$this->screen_name		= $arr['screen_name'];
		$this->user_name		= $arr['user_name'];
		$this->tweet_text		= $arr['tweet_text'];
		$this->tweet_id			= $arr['id_str'];
		$this->retweeter		= $arr['retweeter'];
		$this->retweet_count	= $arr['retweet_count'];
		$this->favorite_count	= $arr['favorite_count'];
		$this->media			= $arr['media'];
	}
	
	/** ----------------------------------------------------------------------*\
	 * Getters
	\** ----------------------------------------------------------------------*/
	public function getDate() {
		return $this->date;
	}
	
	public function getImageUrl() {
		return $this->image_url;
	}
	
	public function getScreenName() {
		return $this->screen_name;
	}
	
	public function getUserName() {
		return $this->user_name;
	}
	
	public function getTweetText() {
		return $this->tweet_text;
	}
	
	public function getId() {
		return $this->tweet_id;
	}
	
	public function getRetweeter() {
		return $this->retweeter;
	}
	
	public function getRetweetCount() {
		return $this->retweet_count;
	}
	
	public function getFavoriteCount() {
		return $this->favorite_count;
	}
	
	public function getMedia() {
		return $this->media;
	}
}