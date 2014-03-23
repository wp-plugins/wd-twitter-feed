<?php
/**
 * @package		AskupaTwitterFeed
 * @subpackage	AskupaOptionsFramework
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/wordpress-options-framework
 * @copyright	2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * Field Validator
 * 
 * This class is used by the Field class (and it's child-classes) and provide
 * static methods to validate a user input
\*----------------------------------------------------------------------------*/

class FieldValidator {
	static function validate($input, $type, $options = null) {
		switch($type) {			case ValidationType::NUMBER:
				if(!is_numeric($input))
					throw new \Exception('The input must be a number');
				break;			case ValidationType::INTEGER_RANGE_STEP:
				if($input % $options['step'] != 0)
					throw new \Exception('The input must be a multiple of ' . $options['step']);			case ValidationType::INTEGER_RANGE:
				if($input < $options['min'] || $input > $options['max'])
					throw new \Exception('The input must be between ' . $options['min'] . ' and ' . $options['max']);			case ValidationType::INTEGER:
				if(!is_integer($input))
					throw new \Exception('The input must be an integer');
				break;			case ValidationType::FLOAT:
				if(!is_float($input))
					throw new \Exception('The input must be a float');
				break;
		}
	}
}

abstract class ValidationType {
	const __default = self::NUMBER;
    const NUMBER = 0;
	const INTEGER = 1;
	const INTEGER_RANGE = 2;
	const INTEGER_RANGE_STEP = 3;
	const FLOAT = 4;
	const FLOAT_RANGE = 5;
	const FLOAT_RANGE_STEP = 6;
	const COLOR = 7;
	const ALPHANUMERIC = 8;
}