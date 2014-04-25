<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

class Checkbox extends Field {
	
	public function __construct($settings) {
		$this->default_settings = array();
		$this->pattern = '<input id="{name}" name="{name}" type="checkbox" class="checkbox {class}" value="{value}" {checked} {disabled}/>';
		$this->pattern .= '<label for="{name}">{label}</label>';
		
		$settings['disabled'] = $settings['disabled'] ? 'disabled' : '';
		$settings['value'] = $settings['value'] ? 1 : 0;
		$settings['checked'] = $settings['value'] ? 'checked' : '';
		$settings['class'] = 'checkbox ' . self::FRAMEWORK . '-field' . ($settings['ignore'] ? ' ' . self::FRAMEWORK . '-ignore' : '');
		$this->settings = $settings;
		
		$this->init();
	}
	
	public static function validate($input, $options = null) {
		return $input;
	}	
}