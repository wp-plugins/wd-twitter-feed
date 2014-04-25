<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*------------------------------------------------------------*\
 * Twitter Feed Options
 * 
 * This class defines the plugin's options page. It extends
 * Askupa's option framework class.
\*------------------------------------------------------------*/

class TwitterFeedOptions extends \AMARKAL\AskupaOptionsFramework {
	/**
	 * Constructor
	 * 
	 * Defines the configuration variable and calls the
	 * parent's init() function
	 */
	protected function __construct() {
		
		
		// Call parent's init function
		include('config.inc.php');
		parent::init($config);
	}
	
	/*------------------------------------------------------------*/
	/* Custom button functions
	/*------------------------------------------------------------*/
	
	/**
	 * Clear Cache
	 */
	public function clear_cache() {
		// Clear the cache
		$this->options['cache_data'] = null;
		
		// Update database
		update_option( self::get_option_name(), $this->options );
		
		// Give feedback
		$this->add_submit_feedback('The cache has been cleared', 'positive');
	}
	
	/**
	 * Send Report
	 */
	public function send_report() {
		$user_id = get_current_user_id();
		$user_email = get_userdata($user_id)->user_email;
		echo 'email = ' . $user_email;
		
		$to = 'bugreport@askupasoftware.com';
		$subject = 'Error Report: Askupa Twitter Feed';
		$headers = 'MIME-version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset= iso-8859-1' . "\r\n";
		$headers .= 'From: ' . $user_email . "\r\n"; //TODO: Fix from/to email
		$headers .= 'Reply-To: ' . $user_email . "\r\n" .
		$headers .= 'X-Mailer: PHP/' . phpversion();

		// The message
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		if($_POST['error_message'] != null) {
			$message = wordwrap($_POST['error_message'], 70, "\r\n");

			// Send
			$success = mail( $to, $subject, $message, $headers );
		}
		
		// Give feedback
		if($success)
			$this->add_submit_feedback('Your error report has been succesfully sent', 'positive');
		else
			$this->add_submit_feedback('An error occurred while trying to send your error report', 'negative');
	}
}