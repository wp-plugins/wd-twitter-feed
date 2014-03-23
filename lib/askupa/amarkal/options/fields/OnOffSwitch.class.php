<?php
/**
 * @package		AskupaTwitterFeed
 * @subpackage	AskupaOptionsFramework
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/wordpress-options-framework
 * @copyright	2014 Askupa Software
 */

namespace TWITTERFEED;

class OnOffSwitch extends Field {
	
	public function __construct($settings) {
		$this->default_settings = array(
			'label-on' => 'ON',
			'label-off' => 'OFF',
			'disabled' => false
		);
		
		$pattern .= '<div class="{container-class}" data-disabled="{disabled}">';
		$pattern .= '<div class="label-on switch-label">{label-on}</div>';
		$pattern .= '<div class="switch-label-divider"></div>';
		$pattern .= '<div class="label-off switch-label">{label-off}</div>';
		$pattern .= '<input type="hidden" name="{name}" class="{class}" value="{value}" /></div>';
		$this->pattern = $pattern;
		
		$settings['container-class'] = 'askupa-switch'.($settings['disabled'] ? ' disabled' : '');
		$settings['class'] = self::FRAMEWORK . '-field' . ($settings['ignore'] ? ' ' . self::FRAMEWORK . '-ignore' : '');
		$settings['value'] = $settings['value'] ? 1 : 0;
		$this->settings = $settings;
		
		$this->init();
	}
	
	public static function validate($input, $options = null) {
		return $input;
	}	
}
