<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

class MultiField {
	private $name;
	private $fields;
	private $label;
	private $description;
	
	public function __construct() {}
	
	/*------------------------------------------------------------------------*\
	 * Getters
	\*------------------------------------------------------------------------*/
	
	public function get_name() {
		return $this->name;
	}

	public function get_fields() {
		return $this->fields;
	}

	public function get_label() {
		return $this->label;
	}

	public function get_description() {
		return $this->description;
	}
	
	/*------------------------------------------------------------------------*\
	 * Setters
	\*------------------------------------------------------------------------*/
	
	public function set_name($name) {
		$this->name = $name;
		return $this;
	}

	public function set_fields($fields) {
		$this->fields = $fields;
		return $this;
	}

	public function set_label($label) {
		$this->label = $label;
		return $this;
	}

	public function set_description($description) {
		$this->description = $description;
		return $this;
	}
	
	public function add_field(Field $field) {
		$this->fields[] = $field;
	}
}

?>
