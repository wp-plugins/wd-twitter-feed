<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * Scrolling Tweet List View
 * 
 * This class extends the TweetsView class and creates an html output to display
 * a scrolling tweet list
\*----------------------------------------------------------------------------*/

class ScrollingTweetsView extends TweetsView {
	
	private $cssClass = 'twitter-feed scrolling-tweet-list';
	
	/**
	 * Display
	 * 
	 * Generate an html formatted tweet list view
	 * 
	 * @return string The html output
	 */
	public function display() {
		$output  = '<div class="'.$this->cssClass.' wrapper '.$this->options['skin'].'" data-scroll-time="'.$this->options['scroll_time'].'">';
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
	private function displayTweet(Tweet $tweet) {		$date = new \DateTime($tweet->getDate());		$output = '<div class="'.$this->cssClass.' tweet-wrapper '.$this->options['skin'].'">';		$output .= '<div class="'.$this->cssClass.' tweet-text '.$this->options['skin'].'">';
		$output .= '<p><span>'.$tweet->getUserName().': </span>';
		$output .= $this->linkifyTweets($tweet->getTweetText(), true).'</p>';
		$output .= '</div>';		$output .= '<time class="'.$this->cssClass.' tweet-time '.$this->options['skin'].'" datetime="'.$date->format($date->W3C).'" title="Tweet Time">';
		$output .= date_i18n(get_option('date_format'), $date->getTimestamp());
		$output .= '</time>';
		
		$output .= '</div>';
		return $output;
	}
}