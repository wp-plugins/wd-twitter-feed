<?php
/** --------------------------------------------------------------------------*\
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
\** --------------------------------------------------------------------------*/

namespace TWITTERFEED;

/** --------------------------------------------------------------------------*\
 * Shortcodes
 * 
 * This class loads and registers all the twitter feed shortcodes.
 * The class follows the singleton design pattern.
\** --------------------------------------------------------------------------*/
class Shortcode {
	
	private static $instance = null;
	private static $parser;
	
	/**
	 * Private constructor to prevent instantiation
	 */
	private function __constract() {}
	
	/** ----------------------------------------------------------------------*\
	 * Return an instance of this class.
	 * @return Shortcodes The only instance of this class
	\** ----------------------------------------------------------------------*/
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$parser = TweetsParser::get_instance();
		}

		return self::$instance;
	}
	
	/** ----------------------------------------------------------------------*\
	 * Register all the shortcodes to be used
	 * by wordpress
	\** ----------------------------------------------------------------------*/
	public function register() {
		add_shortcode( 'statictweets', array( $this, 'statictweets' ));
		add_shortcode( 'scrollingtweets', array( $this, 'scrollingtweets' ));
		add_shortcode( 'slidingtweets', array( $this, 'slidingtweets' ));
	}
	
	/** ----------------------------------------------------------------------*\
	 * Static Tweet List shortcode
	\** ----------------------------------------------------------------------*/
	public function statictweets( $atts ) {
		
		// Generate the assets array
		$assets = array();
		$show_arr = explode( ',', $atts['show'] );
		foreach( $show_arr as $show ) {
			$show = explode( ':', $show );
			$assets[$show[0]] = 'true' == $show[1]  ? true : false;
		}
		
		// Do the shortcode
		return static_tweets( $atts, $assets );
	}
	
	/** ----------------------------------------------------------------------*\
	 * Scrolling Tweet List shortcode
	\** ----------------------------------------------------------------------*/
	public function scrollingtweets( $atts ) {
		
		// Do the shortcode
		return scrolling_tweets( $atts );
	}
	
	/** ----------------------------------------------------------------------*\
	 * Sliding Tweet List shortcode
	\** ----------------------------------------------------------------------*/
	public function slidingtweets( $atts ) {
		
		// Generate the assets array
		$assets = array();
		$show_arr = explode( ',', $atts['show'] );
		foreach( $show_arr as $show ) {
			$show = explode( ':', $show );
			$assets[$show[0]] = 'true' == $show[1]  ? true : false;
		}
		
		// Do the shortcode
		return sliding_tweets( $atts, $assets );
	}
}