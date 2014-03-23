<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/**
 * Debug class
 * 
 * This class has only one instance (singleton design pattern).
 * Used as a database for debugging purposes. Any class can
 * send a message to this class, and add that message to the 
 * list of debug messages.
 */
class Debug {
	
	private $debug_data;
	private static $instance = null;
	
	/**
	 * Return an instance of this class
	 * 
	 * @return TweetsParser The only instance of this class
	 */
	public static function get_instance() {		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/**
	 * Append a message to the debug data
	 * 
	 * @param	type	$message	The message to add to the queue
	 */
	public function append($message) {
		$callers = debug_backtrace();
		$data['function'] = $callers[1]['function'];
		$data['message'] = $message;
		$data['class'] = $callers[1]['class'];
		$this->debug_data[] = $data;
	}
	
	/**
	 * Get the debug data array
	 * 
	 * @return array The debug messaged array
	 */
	public function get_data() {
		return $this->debug_data;
	}
	
	/**
	 * Display the data in HTML formatting
	 */
	public function get_data_as_html() {
		$output  = '<div id="askupa-twitter-feed-debug">';
		$output .= '<h2>Debug Window<i id="askupa-close-debug-window" class="fa fa-times"></i></h2>';
		$output .= '<div class="box"><ol>';
		foreach($this->get_data() as $data) {
			$output .= '<li><strong>'.$data['class'].'->'.$data['function'].':</strong> '.$data['message'].'</li>';
		}
		$output .= '</ol></div></div>';
		return $output;
	}
}
