<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

// Required resources
require_once('ResourceLoader.class.php');

/**
 * This is the base class to every plugin.
 * it contains all the neccessary functionalities with
 * which a WordPress plugin can be created easily 
 * and rapidly
 */
abstract class Amarkal {
	
	/* Non-static vriables */
	protected static $config;
	protected $files;
	protected $resource_loader;
	
	/* Static variables */
	// Only instance (allows a single instance for each child)
	private static $_instances = array();

	// Private constructor to prevent instantiation
	protected function __construct() {}
	
	/**
	 * Initiate
	 */
	protected function init() {
		
		// Instantiate the ResourceLoader
		$this->resource_loader = new ResourceLoader();
		$this->resource_loader->registerNamespace(__NAMESPACE__);
		$this->resource_loader->registerNamespace("TWITTERFEED");
		$this->resource_loader->registerAutoloadFunc();
		
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		// The array's first argument is the name of the plugin defined in `class-plugin-name.php`
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'localize_script' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_script' ) );
		
		// Register ajax callback function for tinymce plugin
		add_action( 'wp_ajax_'.PLUGIN_SLUG.'_thickbox_callback', array($this, 'thickbox_callback') );
	}
	
	/**
	 * Return an instance of this class.
	 * @since 1.0
	 */
	public static function get_instance($basedir = null,$namespace = null) {
        // Get the child class
		$class = get_called_class();
		
		// If the single instance hasn't been set, set it now.
        if (!isset(self::$_instances[$class])) {
			self::generate_defines($basedir,$namespace);
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }
	
	/**
	 * Load the plugin text domain for translation.
	 * @since 1.0
	 */
	public function load_plugin_textdomain() {

		$domain = PLUGIN_SLUG;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, PLUGIN_DIR . 'languages/' );
	}

	/**
	 * Called upon when the plugin is activated
	 * @since 1.0
	 */
	public static abstract function activate( $network_wide );
	
	/**
	 * Called upon when the plugin is deactivated
	 * @since 1.0
	 */
	public static abstract function deactivate( $network_wide );
	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 1.0
	 * @return    null    Return early if no settings page 
	 *					  is registered.
	 */
	public function enqueue_admin_styles() {
		foreach( $this->resource_loader->getAdminStyles() as $style)
			wp_enqueue_style( 
				$this->config['plugin-class'].'-'.$style['name'], 
				$style['url'], 
				$style['dependencies'], 
				$style['version']
			);
	}
	
	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since 1.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		foreach( $this->resource_loader->getAdminScripts() as $script)
			wp_enqueue_script( 
				$this->config['plugin-class'].'-'.$script['name'], 
				$script['url'], 
				$script['dependencies'], 
				$script['version']
			);
	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 * @since 1.0
	 */
	public function enqueue_public_styles() {
		foreach( $this->resource_loader->getPublicStyles() as $style)
			wp_enqueue_style( 
				$this->config['plugin-class'].'-'.$style['name'], 
				$style['url'], 
				$style['dependencies'], 
				$style['version']
			);
	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 * @since 1.0
	 */
	public function enqueue_public_scripts() {
		foreach( $this->resource_loader->getPublicScripts() as $script)
			wp_enqueue_script( 
				$this->config['plugin-class'].'-'.$script['name'], 
				$script['url'], 
				$script['dependencies'], 
				$script['version']
			);
	}
	
	/**
	 * Get the data used for script localization
	 * To set localized data, override this function in the
	 * child class, and have it return and array of the following
	 * structure:
	 * <code>
	 * array(
	 *   array(
	 *     'handle' => 'script file name'
	 *     'object' => 'object name'
	 *     'data'   => 'data array'
	 *   )
	 *   ...
	 *   array()
	 *   ...
	 * )
	 * </code>
	 * 
	 * @since 1.0
	 * @return boolean false unless overridden
	 */
	public function get_local_data() {
		return false;
	}
	
	/**
	 * Localizes a script according to the data
	 * retrieved from get_local_data
	 * 
	 * @since 1.0
	 */
	public function localize_script() {
		if($this->get_local_data() !== false)
			foreach($this->get_local_data() as $data)
				wp_localize_script( $this->config['plugin-class'].'-'.$data['handle'], $data['object'], $data['array'] );
	}
	
	/**
	 * Generate namespace-specific defines
	 * 
	 * @since 1.0 
	 */
	public static function generate_defines($basedir, $namespace) {
		
		// Add slash to namespace
		$namespace = $namespace . '\\';
		
		// Some defines are generated from the bootstrap file headers
		$headers = get_file_data(
			plugin_dir_path( $basedir ) . 'bootstrap.php',
			array(
				'version' => 'Version',
				'version-type' => 'Version Type',
				'name' => 'Plugin Name',
				'slug' => 'Text Domain'
			)
		);
		
		// Assgin defines
		$defines = array(
			'PLUGIN_VERSION' => $headers['version'],
			'PLUGIN_VERSION_TYPE' => $headers['version-type'],
			'PLUGIN_NAME' => $headers['name'],
			'PLUGIN_SLUG' => $headers['slug'],
			'PLUGIN_DIR' => plugin_dir_path( $basedir ),
			'PLUGIN_URL' => plugin_dir_url( $basedir )
		);
		
		// Create a file holding the root directory
		// NOTE: This overwrites the file if it exists
		$dir = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'root.txt';
		self::fstream($dir, 'w+', $basedir);
		
		// Generate defines
		// All defines are shared on all registered namespaces
		foreach($defines as $name => $value) {
			define($namespace . $name , $value);
			define(__NAMESPACE__ . '\\' . $name , $value);
		}
	}
	
	/**
	 * File Stream
	 * 
	 * Read/write/create/manipulate files
	 * 
	 * @see		http://www.php.net/fopen
	 * @param	string	$path	Absolute path to the file
	 * @param	string	$op		Operation (see fopen doc)
	 * @param	string	$data	The data to be written into file
	 */
	private static function fstream($path, $op, $data = null) {
		$handle = fopen($path, $op) or die('Cannot open file:  '.$path);
		if($data !== null)
			fwrite($handle, $data);
		fclose($handle);
	}
	
	/**
	 * Text editor callback function
	 */
	public function thickbox_callback() {
		include( 'popup/callback.php' );
		die();
	}
}