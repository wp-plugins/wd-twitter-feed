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
 * This class extends the TweetListView class and creates an html output to 
 * display a sliding tweet list. 
\*----------------------------------------------------------------------------*/

class SlidingTweetsView extends StaticTweetsView {
	
	protected $cssClass = 'twitter-feed sliding-tweet-list';
	
	/**
	 * Display
	 * 
	 * Generate an html formatted tweet list view
	 * 
	 * @return string The html output
	 */
	public function display() {
		$output  = '<div class="'.$this->cssClass.' wrapper '.$this->options['skin'].'" data-slide-dir="'.$this->options['slide_dir'].'" data-slide-duration="'.$this->options['slide_duration'].'">';
		
		foreach($this->tweets as $tweet) {
			$output .= '<div class="tweet-padder '.$this->cssClass.' '.$this->options['skin'].'">';
			$output .= $this->displayTweet($tweet);
			$output .= '</div>';
		}
		
		$output .= '</div>';
		
		return $output;
	}
	
	/**
	 * Print the HTML code required for a single tweet box
	 * @param Tweet $tweet the tweet to display
	 */
	protected function displayTweet(Tweet $tweet) {
		return parent::displayTweet($tweet);
	}
}