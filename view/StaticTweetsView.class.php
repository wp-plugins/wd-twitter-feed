<?php
/** --------------------------------------------------------------------------*\
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
\** --------------------------------------------------------------------------*/

namespace TWITTERFEED;

/** --------------------------------------------------------------------------*\
 * Tweet List View
 * 
 * This class extends the TweetsView class and creates an html output to display
 * a simple and fixed tweet list
 * @
\** --------------------------------------------------------------------------*/

class StaticTweetsView extends TweetsView {

	/** ----------------------------------------------------------------------*\
	 * Retruns an array of the local variables used to render the tweets
	 * 
	 * @return array The array of render variables
	\** ----------------------------------------------------------------------*/
	public function get_render_variables() {
		
		// Tweet view variables
		$vars = array(
			'class'				=> 'twitter-feed static-tweet-list',
			'skin'				=> $this->options['skin'],
			'slug'				=> $this->textdomain,
			'dir'				=> $this->options['dir'],
			'show_time'			=> $this->options['tweettime'],
			'show_avatar'		=> $this->options['avatar'],
			'show_user_name'	=> $this->options['username'],
			'show_screen_name'	=> $this->options['screenname'],
			'show_actions'		=> $this->options['tweetactions'],
			'tweets'			=> array()
		);
		
		// Tweet-specific variables
		foreach ($this->tweets as $tweet) {
			$date = new \DateTime($tweet->getDate());
			$vars['tweets'][] = array(
				'user_name'			=> $tweet->getUserName(),
				'screen_name'		=> $tweet->getUserName(),
				'image_url'			=> $tweet->getImageUrl(),
				'tweet_text'		=> $this->getTweetText($tweet),
				'time'				=> $date->format($date->W3C),
				'time_formatted'	=> date_i18n(get_option('date_format'), $date->getTimestamp()),
				'retweeter'			=> $tweet->getRetweeter(),
				'media'				=> $tweet->getMedia(),
				'tooltip_attributes'=> 'data-toggle="tooltip" data-placement="top"',
				'intent_url'		=> 'https://twitter.com/intent/',
				'tweet_id'			=> $tweet->getId(),
				'retweet_count'		=> $tweet->getRetweetCount(),
				'favorite_count'	=> $tweet->getFavoriteCount(),
				'show_retweeter'	=> NULL !== $tweet->getRetweeter() && $this->options['retweets'],
				'show_media'		=> $tweet->getMedia() !== NULL && $this->options['media']
			);
		}
		return $vars;
	}
	
	/** ----------------------------------------------------------------------*\
	 * Retrun the HTML representation of the current
	 * Tweets model as scrolling tweets
	 * 
	 * @return string The HTML representation of the tweets
	\** ----------------------------------------------------------------------*/
	public function render() {
		
		extract($this->get_render_variables());
		
		// Render the view
		ob_start();
		include(str_replace('.class', '', __FILE__));
		return ob_get_clean();
	}
}
