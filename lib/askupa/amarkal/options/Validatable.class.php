<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

interface Validatable {
	/**
	 * validate
	 * 
	 * This abstract function must be overriden
	 * by the child class and is used to validate the 
	 * input after form submission.
	 */
	static function validate($input, $options = null);
}