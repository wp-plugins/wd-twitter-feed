<?php
/**
 * @package		AskupaTwitterFeed
 * @subpackage	AskupaOptionsFramework
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/wordpress-options-framework
 * @copyright	2014 Askupa Software
 */

namespace TWITTERFEED;

/**
 * Askupa Options Framework
 */
abstract class AskupaOptionsFramework {
	
	private $config;	private $output;	private $error;	protected $options;	protected $submit_feedback;	private $sections;	private $plugin_screen_hook_suffix = null;
	
	private $FRAMEWORK = 'askupa';	private static $_instances = array();
	
	/*------------------------------------------------------------*/
	/* Private functions
	/*------------------------------------------------------------*/	protected function __construct() {}
	
	/**
	 * Initate framework
	 * 
	 * This is used by the child class when it is instantiated
	 * (in the constructor)
	 * 
	 * @param	array	$config the config array containing 
	 *					all the options and settings
	 */
	protected function init(array $config) {		if($this->is_valid_config($config))
			$this->config = $config;		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );		register_activation_hook( $this->config['plugin-dir'], array($this, 'activate') );
		register_deactivation_hook( $this->config['plugin-dir'], array($this, 'deactivate') );
	}
	
	/**
	 * Validate the config variable to verify unique sections and fields. 
	 * Duplicate fields and/or sections are not allowed.
	 * 
	 * @param type $config the config variable to be validated
	 */
	private function is_valid_config($config) {		foreach($config['sections'] as $section)
			$labels[] = $section['label'];
		if(count($labels) !== count(array_unique($labels)))
			$this->append_error('The config variable contains duplicate section labels');		foreach($config['sections'] as $section)
			if(isset($section['fields']))
				foreach($section['fields'] as $name => $param) {
					$field_names[] = $name;
					if($param['type'] === 'Button')
						$button_labels[] = $param['label'];
			}
		if(count($field_names) !== count(array_unique($field_names)))
			$this->append_error('The config variable contains duplicate field names');
		if(count($button_labels) !== count(array_unique($button_labels)))
			$this->append_error('The config file contains repeating button labels. This is not allowed since the button label is used as the $_POST index for the "function-name" parameter');
		
		return true;
	}
	
	/**
	 * Get Config
	 * 
	 * Returns a filtered or unfiltered copy of the config variable.
	 * If a section was specified, then the filter must be set to 'defaults' and 
	 * the function will return the defaults of that section only.
	 * 
	 * @param	string	$filter	- the type of filter. accepted values are:
	 *					null, 'sections', 'fields', 'defaults'
	 * @param	string	$section_id - the section for which
	 *					the defaults will be returned. If
	 *					null, the defaults of all sections
	 *					will be returned.
	 * @return	array	The defaults of the fields
	 */
	private function get_config($filter = null, $section_id = null) {
		$output = array();		switch($filter) {			case null:
				return $this->config;			case 'sections':
				foreach($this->config['sections'] as $section)
					$output[] = $section;
				break;			case 'fields':
				foreach($this->get_config('sections') as $section)					if(isset($section['fields']))
						foreach($section['fields'] as $name => $param)							if(isset($param['multi-field']))
								foreach($param['multi-field'] as $child_name => $child_param)
									$output[$name][$child_name] = $child_param;							else $output[$name] = $param;
				break;			case 'defaults':
				foreach($this->get_config('sections') as $section)					if(($section_id != null && $section_id == Section::gen_id($section['label'])) || !$section_id)						if(isset($section['fields']))
							foreach($section['fields'] as $name => $param)								if(isset($param['multi-field']))
									foreach($param['multi-field'] as $child_name => $child_param)
										$output[$name][$child_name] = $child_param['default'];								else $output[$name] = $param['default'];
		}
		return $output;
	}
	
	/**
	 * Get Sections
	 * 
	 * Return an array of section objects representing
	 * all the sections as specified in the config variable
	 * 
	 * @return	Section[]	an array of section objects
	 */
	private function get_sections() {		if(!isset($this->sections)) {
			$sections = array();
			foreach($this->get_config('sections') as $section)
				$sections[] = new Section($section, $this->get_options());
			$this->sections = $sections;
		}
		return $this->sections;
	}
	
	/**
	 * Get Section
	 * 
	 * Return a Section object bearing the given
	 * $section_label
	 * 
	 * @param	string		$section_label The label of the
	 *						Section object
	 * @return	Section		The requested section
	 */
	private function get_section($section_label) {
		foreach($this->get_sections() as $section)
			if($section->get_label() == $section_label)
				return $section;
	}
	
	/**
	 * Get the current section after form submission.
	 * Used by JavaScript to display the last edited section
	 * 
	 * @return String The id of the current section
	 */
	private function get_current_section() {
		$current_section = $_POST[ $this->FRAMEWORK.'-current-section' ];
		if(isset($current_section))
			return $current_section;
		else return null;
	}
	
	/**
	 * Get Options
	 * 
	 * Return the most up-to-date plugin options. If this is the
	 * first initiation of the plugin, the defaults (as specified
	 * in the config variable) will be returned. Otherwise, the
	 * options will be retrieved from the database. If the user
	 * has updated the options, this function will first update
	 * the database and then return the updated version of the
	 * options.
	 * 
	 * @return	array	The most up-to-date options
	 */
	private function get_options() {		if(!isset($this->options)) {			$this->options = get_option(self::get_option_name());			if($this->options == null) {
				$this->options = $this->get_config('defaults');
				update_option( self::get_option_name(), $this->options );
			}			if( isset($_POST['custom-button-submit']) ) {
				$func_name = $_POST['custom-button-function'][$_POST['custom-button-submit']];
				$this->$func_name();				return;
			}			if( isset($_POST['update-options']) ) {				$new_options = array();				switch($_POST['update-options']) {					case 'Save Changes':
						$new_options = $this->get_field_post_values();
						break;
					case 'Reset Section':
						$section_defaults = $this->get_config('defaults', $_POST['askupa-current-section']);
						$post_values = $this->get_field_post_values();
						$new_options = array_merge($post_values, $section_defaults);
						break;
					case 'Reset All':
						$new_options = $this->get_config('defaults');
						break;
				}				update_option( self::get_option_name(), $new_options );				$this->options = $new_options;				$this->add_submit_feedback('Your settings were successfully saved', 'positive');
			}
		}
		
		return $this->options;
	}
	
	/**
	 * Append a string to the final ouput string
	 * @param	string	$str	The string to append
	 */
	private function append_output($str) {
		$this->output .= $str;
	}
	
	/**
	 * Print the current output
	 */
	private function print_output() {
		echo $this->output;
	}
	
	/**
	 * Append Error
	 * Add an error string to the error string array
	 * @param	string	$str	The error string to append
	 */
	private function append_error($str) {
		$this->error[] = $str;
	}
	
	/**
	 * Get Field Values
	 */
	private function get_field_post_values() {
		$values = array();
		foreach ($this->get_config('sections') as $section) {
			
			if(!isset($section['fields']))
				continue;
			
			foreach($section['fields'] as $name => $param) {				if(isset($param['multi-field'])) {
					foreach($param['multi-field'] as $child_name => $child_param) {						$child_param['label'] = $param['label'];
						$value = $this->validate_input($_POST[$name][$child_name], $child_param);
						$values[$name][$child_name] = $value;
					}
				}				else {					$value = $_POST[$name];
					$values[$name] = $this->validate_input($value, $param);
				}
			}
		}		return $values;
	}
	
	/**
	 * Validate an input accroding to it's type and options
	 * @param	type $input	The input to validate
	 * @param	type $param	The input's parameters, including the
	 *						type and the options
	 * @return	type		The validated input
	 */
	private function validate_input($input, $param) {		if($param['force-default'])
			return $param['default'];		try {
			$classname = __NAMESPACE__ . '\\' . $param['type'];
			$output = $classname::validate($input, $param);
		} catch(\Exception $e) {
			$this->add_submit_feedback('Error in field "'.$param['label'].'": ' . $e->getMessage(), 'negative');
			$output = $param['default'];
		}
		return $output;
	}
	
	/**
	 * Render Hidden Fields
	 * 
	 * Create a set of hidden fields required for the
	 * 'behind the scene' operation of the options 
	 * framework
	 */
	private function render_hidden_fields() {		$this->append_output('<input id="sections" type="hidden" value="');
		$i = 0;		$sections = $this->get_sections();
		foreach($this->get_sections() as $section) {
			$this->append_output($section->get_id());
			if(++$i != count($sections))
				$this->append_output(',');
		}
		$this->append_output('" />');		$this->append_output('<input type="hidden" name="'.$this->FRAMEWORK.'-current-section" id="'.$this->FRAMEWORK.'-current-section" value="'.$this->get_current_section().'">');		$this->append_output('<input type="hidden" id="'.$this->FRAMEWORK.'-submit-feedback" value="'.htmlentities(json_encode($this->submit_feedback)).'">');
	}
	
	/**
	 * Render Header
	 * 
	 * Renders the header, subheader and feedback section
	 */
	private function render_header() {		$this->append_output(
			'<div class="'.$this->FRAMEWORK.'-header">'.
			'<h2>'.$this->config['title'].'<span>'.$this->config['version'].'</span></h2>'.
			'<img src="'.$this->config['header-icon-url'].'" />'.
			'</div>'
		);		$this->append_output(
			'<div class="'.$this->FRAMEWORK.'-subheader">'.
			'<p>'.$this->config['subtitle'].'</p>'.
			'</div>'
		);		$this->append_output(
			'<div id="'.$this->FRAMEWORK.'-feedback-wrapper"></div>'
		);
	}
	
	/**
	 * Render Sidebar
	 */
	private function render_sidebar() {		$this->append_output(
			'<div class="'.$this->FRAMEWORK.'-sidebar"><ul class="'.$this->FRAMEWORK.'-menu">'
		);		if(isset($this->config['sidebar-structure'])) {
			$i = 0;
			foreach($this->config['sidebar-structure'] as $item) {
				if($item === 'divider')
					$this->append_output('<li class="divider"></li>');
				elseif($item === 'section') {
					$section = $this->get_section($this->config['sections'][$i++]['label']);
					$this->append_output('<li id="'.$section->get_id().'-menu-item"><a href="javascript:void(0);"><i class="fa fa-lg '.$section->get_icon().'"></i>'.$section->get_label().'</a></li>');
				}
			}
		}		else {
			foreach($this->config['sections'] as $section) {
				$section = $this->get_section($section['label']);
				$this->append_output('<li id="'.$section->get_id().'-menu-item"><a href="javascript:void(0);"><i class="fa fa-lg '.$section->get_icon().'"></i>'.$section->get_label().'</a></li>');
			}
		}		$this->append_output('</ul></div>');
	}
	
	/**
	 * Render Footer
	 */
	private function render_footer() {
		$this->append_output(
			'<div class="'.$this->FRAMEWORK.'-footer">'.
			'<img src="'.$this->config['footer-icon-url'].'" />'.
			'<p>'.$this->config['footer-text'].'</p>'.
			'<div class="'.$this->FRAMEWORK.'-actions">'.
			'<input type="submit" name="update-options" id="'.$this->FRAMEWORK.'-save-changes" class="button button-primary" value="Save Changes">'.
			'<input type="submit" name="update-options" id="'.$this->FRAMEWORK.'-reset-section" class="button" value="Reset Section">'.
			'<input type="submit" name="update-options" id="'.$this->FRAMEWORK.'-reset-all" class="button" value="Reset All">'.
			'</div></div>'
		);
	}
	
	/**
	 * Render Main
	 */
	private function render_main() {
		$this->append_output(
			'<div class="'.$this->FRAMEWORK.'-main">'
		);		foreach($this->get_sections() as $section) {			$this->append_output('<div id="'.$section->get_id().'-section" class="'.$this->FRAMEWORK.'-section">');
			$this->append_output('<h2><i class="fa fa-lg '.$section->get_icon().'"></i> '.$section->get_label().'</h2>');
			$this->append_output('<p class="'.$this->FRAMEWORK.'-section-description">'.$section->get_description().'</p>');			$this->append_output($section->get_html());			if(!$section->has_fields()) {
				$this->append_output('</div>');
				continue;
			}			$this->append_output('<table class="form-table"><tbody>');
			foreach($section->get_fields() as $field) {				if($field instanceof Hidden) {
					$this->append_output($field->get_output());
					continue;
				}
				
				$this->append_output('<tr valign="top">');
				$this->append_output('<th scope="row">'.$field->get_label().'<br /><span class="description">'.$field->get_description().'</span></th>');
				$this->append_output('<td>');				if($field instanceof MultiField)
					foreach($field->get_fields() as $child_field)
						$this->append_output($child_field->get_output());
				else $this->append_output($field->get_output());				$this->append_output('</td></tr>');
			}
			$this->append_output('</tbody></table></div>');
		}
		$this->append_output('</div>');
	}
	
	/*------------------------------------------------------------*/
	/* Wordpress related functions
	/*------------------------------------------------------------*/
	
	/**
	 * Register the administration menu for this plugin into
	 * the WordPress Dashboard menu.
	 */
	public function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_plugins_page(
			__( $this->config['wp-page-title'], $this->config['plugin-slug'] ), // Page title
			__( $this->config['wp-menu-item'], $this->config['plugin-slug'] ), // Menu item title
			'manage_options',
			$this->config['plugin-slug'],
			array( $this, 'render' )
		);
	}
	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @return    null    Return early if no settings page 
	 *					  is registered.
	 */
	public function enqueue_admin_styles() {		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->FRAMEWORK . '-options-framework-styles', self::get_framework_url() . 'askupa-options-framework.css', array(), $this->version );
			wp_enqueue_style( 'font-awesome', 'http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', array(), '4.0.3' );
			wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css', array(), '1.10.3');			wp_enqueue_style( 'wp-color-picker' );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 * 
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {		if ( !isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}		$screen = get_current_screen();
		if( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', array('jquery'), '1.10.3');
			wp_enqueue_script( $this->FRAMEWORK . '-options-framework-script', self::get_framework_url() . 'askupa-options-framework.js', array('jquery'), $this->version );
		}
		

	}
	
	/*------------------------------------------------------------*/
	/* Public functions
	/*------------------------------------------------------------*/
	
	/**
	 * Return an instance of this class
	 * 
	 * @return The only instance of this class
	 */
	public static function get_instance() {
        // Get the child class
		$class = get_called_class();        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }
	
	/**
	 * Render the entire page with all its elements 
	 * using the information from the config variable
	 */
	public function render() {		$this->append_output(
			'<div class="'.$this->FRAMEWORK.'-wrapper">'.
			'<noscript>Warning- This options panel will not work properly without javascript!</noscript>'.
			'<form method="post" action="" enctype="multipart/form-data" id="'.$this->FRAMEWORK.'-form">'
		);		$this->render_header();		$this->render_sidebar();		$this->render_main();		$this->append_output('<div class="clear"></div>');		$this->render_footer();		$this->render_hidden_fields();		$this->append_output('</form></div>');		if($this->error != null)
			foreach($this->error as $error)
				$this->add_submit_feedback ($error, 'negative');		$this->print_output();
	}
	
	/**
	 * 
	 * @param type $message
	 * @param type $type
	 */
	public function add_submit_feedback($message, $type) {
		$this->submit_feedback[] = array(
			'message' => $message,
			'type' => $type
		);
	}
	
	/**
	 * Get the option name under which all the 
	 * options data is stored in the database.
	 * This can be used with Wordpress' get_option(), 
	 * update_option() etc...
	 * 
	 * @return	type	the option name
	 */
	public function get_option_name() {
		return str_replace(' ', '-', strtolower($this->config['option_name']));
	}
	
	/**
	 * Get framework dir
	 * @return The directory as a path
	 */
	public static function get_framework_dir() {
		return plugin_dir_path( __FILE__ );
	}
	
	/**
	 * Get framework url
	 * @return The directory as a url
	 */
	public static function get_framework_url() {
		return plugin_dir_url( __FILE__ );	
	}
	
	/**
	 * Called upon when the plugin/theme is activated
	 */
	public static function activate() {	}
	
	/**
	 * Called upon when the plugin/theme is deactivated
	 */
	public static function deactivate() {
		delete_option( $this->get_option_name() );
	}
	
	/**
	 * Get the contents of a file as a string.
	 * Any php will be parsed and evaluated.
	 * 
	 * @param	string	$path the path of the file 
	 *					(do not use urls, since they might be blocked by PHP)
	 * @return	string	the content of the file
	 */
	public function include_as_string($path) {		$path = PLUGIN_DIR . $path;
		
		if(file_exists($path)) {
			ob_start();
			include( $path );
			return ob_get_clean();
		}
		else return '<div class="askupa-feedback negative" id="askupa-feedback">The file '.$path.' could not be loaded</div>';
	}
}