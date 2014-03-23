<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * Shortcodes
 * 
 * This class loads and registers all the twitter feed shortcodes.
 * The class follows the singleton design pattern.
\*----------------------------------------------------------------------------*/

class Shortcode {
	
	private static $instance = null;
	private static $parser;
	
	/**
	 * Private constructor to prevent instantiation
	 */
	private function __constract() {}
	
	/**
	 * Return an instance of this class.
	 * @return Shortcodes The only instance of this class
	 */
	public static function get_instance() {		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$parser = TweetsParser::get_instance();
		}

		return self::$instance;
	}
	
	/**
	 * Register all the shortcodes to be used
	 * by wordpress
	 */
	public function register() {
		add_shortcode( 'statictweets', array( $this, 'statictweets' ));
		add_shortcode( 'scrollingtweets', array( $this, 'scrollingtweets' ));
		add_shortcode( 'slidingtweets', array( $this, 'slidingtweets' ));
	}
	
	/**
	 * Show error
	 * @param	string	$message The error message
	 * @return	string	The html formatter error message
	 */
	private function show_error($message) {
		$output = '<div class="askupa-twitter-error"><strong>Error:</strong> '.$message.'</div>';
		return $output;
	}
	
	/**
	 * Static Tweet List shortcode
	 */
	public function statictweets( $atts ) {
		extract( $atts );		$options['dir'] = $dir;	
		$options['skin'] = $skin;	
		$show = explode(',', $show);
		foreach($show as $s) {
			$s = explode(':', $s);
			$options[$s[0]] = $s[1] == 'true' ? true : false;
		}		try {
			$tweets = self::$parser->getTweets(array(
				'user' => $user,
				'count' => $count,
				'replies' => $options['replies'],
				'retweets' => $options['retweets'],
				'resource' => $resource,
				'list' => $list,
				'query' => $query
			));
		} catch (\Exception $e) {
			return do_shortcode( $this->show_error($e->getMessage()) );
		}		$tweetview = new StaticTweetsView($tweets, $options);		$html = $tweetview->display();		return do_shortcode( $html );
	}
	
	/**
	 * Scrolling Tweet List shortcode
	 */
	public function scrollingtweets( $atts ) {
		extract( $atts );		try {
			$tweets = self::$parser->getTweets(array(
				'user' => $user,
				'count' => $count,
				'replies' => false,
				'retweets' => false,
				'resource' => $resource,
				'list' => $list,
				'query' => $query
			));
			
		} catch (\Exception $e) {
			return do_shortcode( $this->show_error($e->getMessage()) );
		}		$options['dir'] = $dir;	
		$options['skin'] = $skin;
		$options['scroll_time'] = $scroll_time;		$tweetview = new ScrollingTweetsView($tweets, $options);		$html = $tweetview->display();		return do_shortcode( $html );
	}
	
	/**
	 * Sliding Tweet List shortcode
	 */
	public function slidingtweets( $atts ) {
		extract( $atts );		try {
			$tweets = self::$parser->getTweets(array(
				'user' => $user,
				'count' => $count,
				'replies' => $options['replies'],
				'retweets' => $options['retweets'],
				'resource' => $resource,
				'list' => $list,
				'query' => $query
			));
		} catch (\Exception $e) {
			return do_shortcode( $this->show_error($e->getMessage()) );
		}		$options['dir'] = $dir;	
		$options['skin'] = $skin;
		$options['slide_dir'] = $slide_dir;
		$options['slide_duration'] = $slide_duration;
		$show = explode(',', $show);
		foreach($show as $s) {
			$s = explode(':', $s);
			$options[$s[0]] = $s[1] == 'true' ? true : false;
		}		$tweetview = new SlidingTweetsView($tweets, $options);		$html = $tweetview->display();		return do_shortcode( $html );
	}
}