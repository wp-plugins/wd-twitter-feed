<?php
/**
 * @package		AskupaTwitterFeed
 * @subpackage	AskupaOptionsFramework
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/wordpress-options-framework
 * @copyright	2014 Askupa Software
 */

namespace TWITTERFEED;

class Textfield extends Field {
	
	public function __construct($settings) {
		$this->default_settings = array();
		$this->pattern = '<input name="{name}" type="text" class="{class}" value="{value}" {disabled} />';
		
		$settings['disabled'] = $settings['disabled'] ? 'disabled' : '';
		$settings['class'] = self::FRAMEWORK . '-field' . ($settings['ignore'] ? ' ' . self::FRAMEWORK . '-ignore' : '');
		$this->settings = $settings;
		$this->init();
	}
	
	public static function validate($input, $options = null) {
		return $input;
	}	
}