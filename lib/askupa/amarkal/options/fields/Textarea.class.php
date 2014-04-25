<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

class Textarea extends Field {
	
	public function __construct($settings) {
		$this->default_settings = array();
		
		$pattern = '<textarea name="{name}" class="{class}" {disabled}>{value}</textarea>';
		$this->pattern = $pattern;
		
		$settings['disabled'] = $settings['disabled'] ? 'disabled' : '';
		$settings['class'] = self::FRAMEWORK . '-field' . ($settings['ignore'] ? ' ' . self::FRAMEWORK . '-ignore' : '');
		$this->settings = $settings;
		
		$this->init();
	}
	
	public static function validate($input, $options = null) {
		return $input;
	}	
}