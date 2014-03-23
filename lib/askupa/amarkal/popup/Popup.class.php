<?php 
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 *	Popup Class
 *
 * This class is included in every shortcode editor.
 * The class creates the html and the jQuery code 
 * for the shortcode editor
\*----------------------------------------------------------------------------*/

class Popup {
	var $item;
	var $shortcode;
	var $child_shortcode;
	var $output;
	var $param;
	var $errors;
	var $config;
	var $plugin_url;
		
	
	/*------------------------------------------------------------------------*/
	/* Constructor
	/*------------------------------------------------------------------------*/
	
	function __construct( $item ) {
		
		if( !$item ) {
			$this->append_error('No shortcode name was defined');
			exit;
		}
		
		$this->config = dirname( dirname( dirname( dirname(__DIR__) ) ) ) . '/config/popup/config.php';
		$this->plugin_url = dirname(__FILE__) . '/../';		if( file_exists( $this->config ) ) {
			
			$this->item = $item;
			
			$this->generate_popup();
		
		} else {
			
			$this->append_error('Config.php could not be loaded');
		}
	}
	
	
	/*------------------------------------------------------------------------*/
	/* Destructor
	/*------------------------------------------------------------------------*/
	
	function __destruct() {
		if( !empty( $this->errors ) )
			echo $this->errors;
	}


	/*------------------------------------------------------------------------*/
	/* Generate The Popup Window
	/*------------------------------------------------------------------------*/

	function generate_popup() {		require_once( $this->config );		if( ! isset( $config[ $this->item ] ) ) {			$this->append_error( $this->item . ' index could not be found in ' . $this->config );			return;
		}		if( isset( $config[ $this->item ]['param'] ) ) {
			
			$this->param = $config[ $this->item ]['param'];
			
			foreach( $this->param as $name => $prop ) {
			
				if(is_string($prop)) 
					$this->generate_divider($prop);
				else
					$this->generate_field($name, $prop);
			}
		}		$this->shortcode = $config[ $this->item ]['shortcode'];
		$this->append_output( '<div id="shortcode" class="hidden">' . $this->shortcode . '</div>' );
	}	
	
	/**
	 * Generate Divider
	 * 
	 * Generate a divider with a label (optional).
	 * The divider config format is as follows:
	 * divider[label] where the text within the square
	 * brackets is used as the text for the label
	 * 
	 * @param	type	$in		config text
	 */
	function generate_divider($in) {		$regex = '#\[(.*?)\]#';
		$out = array();
		if(preg_match($regex, $in, $out))
			$title = '<th>' . $out[1] . '</th><td></td>';
		else $title = '';
		$this->append_output( '<tr class="askupa-divider">' . $title . '</tr>' );
	}
	
	/**
	 * Generate Field
	 * 
	 * Generate an html formatted field
	 * 
	 * @param type $name
	 * @param type $prop
	 */
	function generate_field($name, $prop) {		$this->append_output( '<tr><th scope="row"><label for="'.$name.'">'.$prop['label'].'</label>' );		$this->append_output( '<p class="description">'.$prop['description'].'</p></th><td>' );
		
		switch( $prop['type'] ) {	
			case 'text':
				$this->append_output( '<input id="'.$name.'" name="'.$name.'" type="text" value="'.$prop['default'].'" />' );
				break;

			case 'number':
				$this->append_output( '<input id="'.$name.'" name="'.$name.'" type="number" value="'.$prop['default'].'" min="'.$prop['min'].'" max="'.$prop['max'].'" step="1" />' );
				break;

			case 'checkbox':
				$i = 0;
				foreach($prop['options'] as $option => $value) {
					$checked = $value ? 'checked ' : '';
					$name = strtolower(str_replace(' ', '', $option));
					$this->append_output( '<div><input id="'.$name.'" name="'.$name.'" type="checkbox" '.$checked.'/>' );
					$this->append_output( '<label for="'.$name.'">'.$option.'</label></div>' );
					$i++;
				}
				break;

			case 'textarea':
				$this->append_output( '<textarea name="'.$name.'" class="large-text">'. $prop['default'] .'</textarea>' );
				break;

			case 'dropdown':					
				$this->append_output( '<select id="'.$name.'" name="'.$name.'">' );

				foreach( $prop['options'] as $key => $value )
					$this->append_output( '<option value="'.$value.'">'.$key.'</option>' );					

				$this->append_output( '</select>' );
				break;
			
			case 'blank':	
				$this->append_output( '<p>'.$prop['text'].'</p>' );
				break;
		}		$this->append_output( '</td></tr>' );
	}
	
	/*------------------------------------------------------------------------*/
	/* Append Output
	/*------------------------------------------------------------------------*/
	
	function append_output( $output ) {
		$this->output = $this->output . "\n" . $output;
	}
	
	/*------------------------------------------------------------------------*/
	/* Append Error
	/*------------------------------------------------------------------------*/
	
	function append_error( $text ) {
		$this->errors = $this->errors . '<br />' . $text;
	}
	
	
	/*------------------------------------------------------------------------*/
	/* Print the output
	/*------------------------------------------------------------------------*/
	
	function show() {
		echo $this->output;
	}
}