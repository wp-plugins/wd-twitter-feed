<?php
/**
 * @package		AskupaTwitterFeed
 * @subpackage	AskupaWidgetFramework
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/wordpress-widget-framework/
 * @copyright	2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * Widget Class
 * 
 * This class can be used as a framework for easily creating widgets for 
 * Wordpress. The class provides a method to create the widget's admin panel 
 * using a simple configuration file.
 * To use this framework, simply create a child class that extends this class, 
 * call parent::init() from the child class' constructor and pass a configuraion
 * variable. The framework is built to support multi-widget.
\*----------------------------------------------------------------------------*/

abstract class Widget extends \WP_Widget {	protected static $FRAMEWORK_URI;	protected static $FRAMEWORK_PATH;	private $config;	private $defaults;	private $widget_slug;	private $classname;	private $basedir;	const FRAMEWORK = 'askupa';	
	
	/**
	 * Initiate
	 * Specifies the classname and description, instantiates 
	 * the widget, loads localization files, and includes
	 * necessary stylesheets and JavaScript.
	 * 
	 * @param type $config the array holding the widget configuration
	 */
	public function init($config) {		$this->set_config($config);		$this->widget_slug = $this->get_classname() . '-locale';
		$this->cssClass = str_replace(' ', '-', strtolower( $this->config['name'] ));		parent::__construct(
			$this->get_classname() ,
			__( $this->config['name'] , $this->widget_slug ), // This is shown in the 'widgets' panel
			array(
				'classname'		=>	$this->get_classname() ,
				'description'	=>	__( $this->config['description'], $this->widget_slug )
			)
		);		self::$FRAMEWORK_PATH = plugin_dir_path( __FILE__ );
		self::$FRAMEWORK_URI = plugin_dir_url( __FILE__ );		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );		add_action( 'init', array( $this, 'widget_textdomain' ) );		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
	}
	
	/**
	 * Set the configuration variable
	 * 
	 * @param type $config the array holding the widget configuration
	 */
	protected function set_config($config) {
		$this->config = $config;
	}
	
	/**
	 * Set the project's base directory
	 * @param type $dir the dir to be set
	 */
	public function set_basedir($dir) {
		$this->basedir = $dir;
	}
	
	public function plugin_dir_url() {
		return plugin_dir_url($this->basedir);
	}
	
	public function plugin_dir_path() {
		return plugin_dir_path($this->basedir);
	}
	
	/**
	 * Get the widget's classname. This is used by the
	 * parent's constructor and by the script/stylesheet
	 * enqueue functions
	 */
	protected function get_classname() {		if(!isset($this->classname)) {			$this->classname = preg_replace('/[^a-zA-Z0-9]/s', '', strtolower($this->config['name']));
		}
		
		return $this->classname;
	}
	
	/**
	 * Generate the defaults from the configuration variable.
	 * These defaults are used by the update function.
	 */
	private function get_defaults() {		if(!isset($this->defaults)) {
			foreach($this->config['form'] as $name => $param)
				if($param['type'] != 'hr')
					$this->defaults[$name] = $param['default'];
		}		return $this->defaults;
	}
	
	/**
	 * Generates the administration form for the widget
	 * 
	 * @param array $instance	The array of keys and 
	 *							values for the widget
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->get_defaults() );
		echo $this->render_form($instance );
	}
	
	/**
	 * Render Form
	 * 
	 * @param	type	$instance	The current widget's user options
	 * @return	string	$output		The html formatted form
	 */
	private function render_form($instance) {
		
		$formfields = $this->config['form'];
		
		$output = '<div class="widget-form">';		foreach($formfields as $name => $param) {
			
			$output .= '<p>'.$param['before'];
			$label = '<label for="'.$this->get_field_id( $name ).'">'.__($param['label'], $this->widget_slug).'</label>';
			switch($param['type']) {
				case 'text':
					$output .= $label;
					$output .= '<input id="'.$this->get_field_id( $name ).'" name="'.$this->get_field_name( $name ).'" type="text" value="'.$instance[$name].'" class="widefat">';
					break;
				case 'number':
					$output .= $label;
					$output .= '<input id="'.$this->get_field_id( $name ).'" name="'.$this->get_field_name( $name ).'" type="number" min="'.$param['min'].'" max="'.$param['max'].'" step="1" value="'.$instance[$name].'" class="small-text widefat alignright" />';
					break;
				case 'checkbox':
					$output .= '<input class="checkbox" type="checkbox" '.( $instance[$name] == 'on' ? 'checked' : '' ).' id="'.$this->get_field_id( $name ).'" name="'.$this->get_field_name( $name ).'" />';
					$output .= $label;
					break;
				case 'color':
					$output .= $label.'<br />';
					$output .= '<input id="'.$this->get_field_id( $name ).'" name="'.$this->get_field_name( $name ).'" type="text" value="'.$instance[$name].'" class="wd-color-field widefat" data-default-color="#'.$param['default'].'" />';
					break;
				case 'dropdown':
					$output .= $label;
					$output .= '<select id="'.$this->get_field_id( $name ).'" name="'.$this->get_field_name( $name ).'" class="widefat">';
					
					foreach( $param['options'] as $key => $value )
						$output .= '<option value="'.$value.'" '.($instance[$name] == $value ? 'selected' : '').'>'.$key.'</option>';					
					
					$output .= '</select>';
					break;
				case 'hr':
					$output .= '<div class="'.self::FRAMEWORK.'-widget-framework hr"><p><span>'.$param['label'].'</span></p><hr /></div>';
					break;
			}
			
			$output .= $param['after'].'</p>';			
		}
		
		$output .= '</div>';
		
		return $output;
	}
	
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param	array	new_instance	The previous instance 
	 *									of values before the update.
	 * @param	array	old_instance	The new instance of 
	 *									values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;		foreach(array_keys( $this->get_defaults() ) as $option) {
			$updated = false;			foreach(array_keys( $this->noStrip ) as $noStriping) {
				if($option == $noStriping) {
					$instance[$option] = $new_instance[$option];
					$updated = true;
					break;
				}
			}			if(!$updated)
				$instance[$option] = strip_tags( $new_instance[$option] );
		}
		
		return $instance;
	}
	
	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {		load_plugin_textdomain( $this->widget_slug , false, $this->config['languages-url'] );
	}
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param		boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public abstract function activate( $network_wide );

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public abstract function deactivate( $network_wide );
	
	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		wp_enqueue_style( 'wp-color-picker' );
	}
	
	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {}
	
	/**
	 * Register and enqueues public facing styles.
	 * This should be overloaded by the child's class
	 * for custom widget styles.
	 */
	public abstract function register_widget_styles();
	
	/**
	 * Register and enqueues public facing scripts.
	 * This should be overloaded by the child's class
	 * for custom widget styles.
	 */
	public abstract function register_widget_scripts();
}
