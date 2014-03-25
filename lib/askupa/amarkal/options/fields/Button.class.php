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
 * Custom User Button
 * 
 * Create a custom button that can be used to fire a custom
 * PHP function after form submission. Note that a label
 * and a function name must be specified. The label is used
 * as the $_POST variable name, and the function name must
 * match the custom function's name that is a method of 
 * the options framework child class
 * 
 * Accepted Fields<br />
 * <b>name</b>		The input name<br />
 * <b>label</b>		The label of the button and the name of the $_POST variable<br />
 * <b>function</b>	The name of the custom php function<br />
 * <b>id</b>		The id of the button. Can be used to bind custom Javascript
 *					functionality.<br />
 */
class Button extends Field {
	
	public function __construct($settings) {
		$this->required = array('label','function');
		$this->default_settings = array();
		
		$pattern = '<input type="hidden" name="custom-button-function[{label}]" value="{function}" />';
		$pattern .= '<input type="submit" name="custom-button-submit" id="{id}" class="button button-primary" value="{label}" />';
		$this->pattern = $pattern;
		
		$this->settings = $settings;
		
		$this->init();
	}

	public static function validate($input, $options = null) {
		return $input;
	}	
}