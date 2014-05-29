<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

class Number extends Field {
	
	public function __construct($settings) {
		$this->default_settings = array(
			'min' => 0,
			'max' => 100,
			'step' => 1
		);
		
		$this->pattern = '<input id="{name}" name="{name}" type="number" class="{class}" value="{value}" min="{min}" max="{max}" step="{step}" {disabled}/>';
		$this->pattern .= '<label for="{name}">{label}</label>';
		
		$settings['disabled'] = $settings['disabled'] ? 'disabled' : '';
		$settings['class'] = 'number ' . self::FRAMEWORK . '-field' . ($settings['ignore'] ? ' ' . self::FRAMEWORK . '-ignore' : '');
		$this->settings = $settings;
		
		$this->init();
	}
	
	public static function validate($input, $options = null) {
		FieldValidator::validate(intval($input), ValidationType::INTEGER_RANGE_STEP, $options);
		return $input;
	}	
}