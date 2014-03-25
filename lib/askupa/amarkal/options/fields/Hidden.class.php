<?php
/**
 * @package		AskupaTwitterFeed
 * @subpackage	AskupaOptionsFramework
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/wordpress-options-framework
 * @copyright	2014 Askupa Software
 */

namespace TWITTERFEED;

/**
 * Hidden field
 * 
 * Hidden fields are not rendered as a part of the administration
 * page. These are useful for creating a custom option field that
 * can be used throughout the website without alowwing the user
 * to change it or see it. 
 */
class Hidden extends Field {
	
	public function __construct($settings) {
		// Defaults
		$this->default_settings = array();
		
		// Pattern
		$this->pattern = '<input name="{name}" type="hidden" value="{value}" />';
		
		// Settings
		$this->settings = $settings;
		
		// Initiate
		$this->init();
	}
	
	public static function validate($input, $options = null) {
		return $input;
	}	
}