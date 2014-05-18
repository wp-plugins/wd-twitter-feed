<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

/*----------------------------------------------------------------------------*\
 * Field class
 * 
 * This class is used as the parent class of any input field. It is abstract
 * and cannot be instantiated. Each field must specify a pattern, setup default
 * values and settings. The abstract function 'validate' must be overriden
 * by the child class and is used to validate the input after form submission.
 * 
 * @property	array	$default_settings	This is used to replace any missing 
 *									parameters that were not set in the 
 *									$settings array
 * @property	array	$settings	This array holds all the settings required 
 *									for the field (name, value etc...)
 * @property	string	$pattern	This is used as the pattern for creating the 
 *									html output. Values inside curley brackets 
 *									will be replaced with their corresponding 
 *									values from the $settings array
\*----------------------------------------------------------------------------*/

abstract class Field implements Validatable {
	
	// Vars
	protected $default_settings;
	protected $settings;
	protected $output;
	protected $pattern;
	
	// Required field properties
	protected $name;				// The name of the field (unique)
	protected $parent_name;			// If this is a child field, this will hold the parent's name
	protected $value;				// The field's raw value
	protected $default_value;		// The field's default value
	protected $label;				// The field's label
	protected $description;			// The description
	protected $field_description;	// The field's description
	protected $ignore;				// Tells the update function to ignore this field
	protected $disabled;			// Disables the field from changes
	
	// Constants
	const FRAMEWORK = 'askupa';
	
	/**
	 * 
	 */
	protected function init() {
		
		// Merge arrays
		$this->settings = array_merge($this->default_settings, $this->settings);
		
		// Set the basic field properties
		$this->default_value = $this->settings['default'];
		$this->label = $this->settings['label'];
		$this->description = $this->settings['description'];
		$this->field_description = $this->settings['field-description'];
		$this->ignore = $this->settings['ignore'] ? true : false;
		$this->disabled = $this->settings['disabled'] ? true : false;
	}
	
	/*------------------------------------------------------------------------*\
	 * Getters
	\*------------------------------------------------------------------------*/
	public function get_output() {
		
		// Lazy instantiation
		if(!isset($this->output)) {
			// Generate the html output
			$pattern = $this->pattern;
			$regex = '/\{(.*?)\}/'; // Replace the keywords inside the curly brackets
			$matches = array();
			preg_match_all($regex, $pattern, $matches);

			// Set the value for html printing (The original value is stored as $this->value)
			$this->settings['value'] = htmlentities(print_r($this->get_value(), true));
			
			// Get the name
			$this->settings['name'] = $this->get_name();

			// Set the attribute values
			foreach($matches[1] as $attr) 
				$pattern = str_replace('{' . $attr . '}', $this->settings[$attr], $pattern);

			// Set the output
			$this->output = $pattern;
			
			// Add the field's description
			$this->output .= '<div class="description '.self::FRAMEWORK.'-field-description">'.$this->get_field_description().'</div>';
		}
		
		return $this->output;
	}
	
	public function get_default() {
		return $this->default_value;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_value() {
		return $this->value;
	}
	
	public function get_label() {
		return $this->label;
	}
	
	public function get_description() {
		return $this->description;
	}
	
	public function get_field_description() {
		return $this->field_description;
	}
	
	public function is_ignored() {
		return $this->ignore;
	}
	
	public function is_disabled() {
		return $this->disabled;
	}

	/*------------------------------------------------------------------------*\
	 * Setters
	\*------------------------------------------------------------------------*/
	
	public function set_pattern($pattern) {
		$this->pattern = $pattern;
	}

	public function set_name($name) {
		$this->name = $name;
	}

	public function set_value($value) {
		$this->value = $value;
	}

	public function set_label($label) {
		$this->label = $label;
	}

	public function set_description($description) {
		$this->description = $description;
	}

	public function set_ignore($ignore) {
		$this->ignore = $ignore;
	}

	public function set_disabled($disabled) {
		$this->disabled = $disabled;
	}
}