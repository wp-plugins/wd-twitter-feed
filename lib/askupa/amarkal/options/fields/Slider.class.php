<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

class Slider extends Field {
	
	public function __construct($settings) {
		$this->default_settings = array(
			'min' => 0,
			'max' => 100,
			'step' => 1
		);
		
		$pattern = '<div class="ui-slider" data-min="{min}" data-max="{max}" data-step="{step}" data-value="{value}" data-disabled="{disabled}">';
		$pattern .= '<input type="text" name="{name}" class="{class}" value="{value}" {disabled-attr}/></div>';
		$this->pattern = $pattern;
		
		$settings['class'] = self::FRAMEWORK . '-field ';
		$settings['class'] .= self::FRAMEWORK . '-slider-input';
		$settings['class'] .= $settings['ignore'] ? ' ' . self::FRAMEWORK . '-ignore' : '';
		$settings['disabled-attr'] = $settings['disabled'] ? 'disabled' : '';
		$this->settings = $settings;
		
		$this->init();
	}
	
	public static function validate($input, $options = null) {
		FieldValidator::validate(intval($input), ValidationType::INTEGER_RANGE_STEP, $options);
		return $input;
	}	
}