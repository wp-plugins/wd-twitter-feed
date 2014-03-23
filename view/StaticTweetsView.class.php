<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * Tweet List View
 * 
 * This class extends the TweetsView class and creates an html output to display
 * a simple and fixed tweet list
\*----------------------------------------------------------------------------*/

class StaticTweetsView extends TweetsView {
	
	protected $cssClass = 'twitter-feed static-tweet-list';
	
	/**
	 * Display
	 * 
	 * Generate an html formatted tweet list view
	 * 
	 * @return string The html output
	 */
	public function display() {
		
		$output   = '<div class="'.$this->cssClass.' wrapper '.$this->options['skin'].'">';
		$output  .= '<div class="'.$this->cssClass.' inner-wrapper '.$this->options['skin'].'">';
		
		foreach($this->tweets as $tweet)
			$output .= $this->displayTweet($tweet);
		
		$output .= '</div></div>';
		
		return $output;
	}
	
	/**
	 * Print the HTML code required for a single tweet box
	 * @param Tweet $tweet the tweet to display
	 */
	protected function displayTweet(Tweet $tweet) {		$date = new \DateTime($tweet->getDate());
		
		$output  = '<div class="'.$this->cssClass.' tweet-wrapper '.$this->options['skin'].'">';		if($this->options['tweettime']) {
			$output .= '<time class="'.$this->options['dir'].'" pubdate="" datetime="'.$date->format($date->W3C).'" title="">';
			$output .= date_i18n(get_option('date_format'), $date->getTimestamp());
			$output .= '</time>';
		}		$output .= '<div class="'.$this->cssClass.' user-card '.$this->options['dir'].'">';		if($this->options['avatar'])
			$output .= '<img src="'.$tweet->getImageUrl().'" width="32" height="32">';
		
		$output .= '<div class="'.$this->cssClass.' screen-name '.$this->options['dir'].'">';		if($this->options['username'])
			$output .= '<span>'.$tweet->getUserName().'</span><br />';		if($this->options['screenname'])
			$output .= '<a href="https://twitter.com/'.$tweet->getScreenName().'" target="_blank" dir="ltr">@'.$tweet->getScreenName().'</a>';
		
		$output .= '</div>';
		$output .= '</div>';		$output .= '<p class="'.$this->cssClass.' tweet-text '.$this->options['dir'].'">'.$this->linkifyTweets($tweet->getTweetText(), true).'</p>';		if($tweet->getRetweeter() != null)
			$output .= '<p class="'.$this->cssClass.' retweet-credits '.$this->options['dir'].'"><i class="fa fa-retweet"></i> '.__('Retweeted by', $this->textdomain).' '.$tweet->getRetweeter().'</p>';		if($this->options['tweetactions']) {
			$wi = 'https://twitter.com/intent/';
			$output .= '<ul class="'.$this->cssClass.' tweet-actions '.$this->options['dir'].' '.$this->options['skin'].'">';
			$output .= '<li><a href="'.$wi.'tweet?in_reply_to='.$tweet->getId().'" class="reply-action web-intent" title="'.__('reply', $this->textdomain).'"><i class="fa fa-reply"></i></a></li>';
			$output .= '<li><a href="'.$wi.'retweet?tweet_id='.$tweet->getId().'" class="retweet-action web-intent" title="'.__('retweet', $this->textdomain).'"><i class="fa fa-retweet"></i></a> '.$tweet->getRetweetCount().'</li>';
			$output .= '<li><a href="'.$wi.'favorite?tweet_id='.$tweet->getId().'" class="favorite-action web-intent" title="'.__('favorite', $this->textdomain).'"><i class="fa fa-star"></i></a> '.$tweet->getFavoriteCount().'</li>';
			$output .= '</ul>';
		}		if($this->options['skin'] == 'talk-bubble-skin')
			$output .= '<i class="fa fa-twitter"></i>';
		
		$output .= '</div>';
		
		return $output;
	}
}
