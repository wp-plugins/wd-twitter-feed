<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

/*----------------------------------------------------------------------------*\
 * The Section class represents a section within the options page
\*----------------------------------------------------------------------------*/

/**
 * Section class
 * 
 * @property Field[]	$fields		An array of Field type objects representing 
 *									all the fields in the section
 * @property array		$settings	An associative array containing all the 
 *									section's setting
 * @property string		$html		Some sections are simply there to provide 
 *									information. Sections that have this property 
 *									must no have fields.
 * @property array		$options	The options (field values) that were retrieved
 *									from the database
 */
class Section {
	
	// Settings array
	private $settings;
	
	// The options property is declared static since 
	// only one instance is needed for all sections
	private static $options;		
	
	// Section properties
	private $fields;
	private $label;
	private $id;
	private $description;
	private $icon;
	private $html;
	
	// Constants
	const FRAMEWORK = 'askupa';
	
	/**
	 * Constructor
	 * 
	 * @param	array	$settings	The section configuration array
	 * @param	array	$options	The values retrieved from the
	 *								database for each Field
	 */
	public function __construct($settings, $options) {
		$this->settings = $settings;
		self::$options = $options;
		//print('<pre>').print_r(debug_zval_dump(self::$options), true).print('</pre>');
		// Set the section's properties
		$this->description = $settings['description'];
		$this->icon = $settings['icon'];
		$this->label = $settings['label'];
		$this->html = $settings['html'];
	}
	
	/**
	 * Get Fields
	 * 
	 * @return Field[]		array of Field objects
	 * @throws Exception	if duplicate fields exists
	 */
	public function get_fields() {
		// Lazy instantiation of fields
		if(!isset($this->fields)) {
		
			// New fields array to hold the Field type objects
			$fields = array();

			// Section has fields
			if($this->settings['fields']) {
				foreach($this->settings['fields'] as $name => $param) {
					
					// Multi-field
					if(isset($param['multi-field'])) {
						$multifield = new MultiField();
						$multifield->set_name($name)
								   ->set_label($param['label'])
								   ->set_description($param['description']);
						foreach($param['multi-field'] as $child_name => $child_param) {
							$classname = __NAMESPACE__ .'\\' . $child_param['type'];
							$field = new $classname($child_param);
							$field->set_name($name . '[' . $child_name . ']');
							$field->set_value(self::$options[ $name ][ $child_name ]);
							$field->set_description($child_param['description']);
							$multifield->add_field($field);
						}
						$fields[] = $multifield;
					}

					// Single field
					else {
						$classname = __NAMESPACE__ .'\\' . $param['type'];
						$field = new $classname($param);
						$field->set_name($name);
						$field->set_value(self::$options[$name]);
						$fields[] = $field;
					}
				}
			}
			
			// Set the field's properties
			$this->fields = $fields;
		}
		// Return an array of field objects
		return $fields;
	}

	public function get_label() {
		return $this->label;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_icon() {
		return $this->icon;
	}

	public function get_html() {
		return $this->html;
	}
	
	public function has_fields() {
		return isset($this->settings['fields']);
	}
	
	/**
	 * Convert a section label to an id string by
	 * removing all special characters, converting
	 * spaces to underscores and converting all letters
	 * to lowercase.
	 * 
	 * @return String The id
	 */
	public function get_id() {
		// Lazy instantiation
		if(!isset($this->id)) {
			$this->id = self::gen_id($this->settings['label']);
		}
		return $this->id;
	}
	
	/**
	 * Generate id
	 * 
	 * Turn a label to an id string. This function is also used to retrieve the
	 * defaults of each section.
	 * 
	 * @param	type	$str The section label to convert to an id
	 * @return	type	The id
	 */
	public static function gen_id($str) {
		// Remove everything but letters and numbers
		$str = preg_replace('/[^a-zA-Z0-9]/s', '', $str);
		// Lowercase letters
		return strtolower($str);
	}
}