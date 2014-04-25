<?php
/**
 * @subpackage	Amarkal
 * @author		Askupa Software <contact@askupasoftware.com>
 * @link		http://products.askupasoftware.com/amarkal/
 * @copyright	2014 Askupa Software
 */

namespace AMARKAL;

/**
 * Resource Loader
 * This class is in charge of loading all the neccessary
 * resources dynamically to increase the modularity of the
 * project.
 */
class ResourceLoader {
	
	// Private members
	private $basedir;
	private $fileTree;
	private $publicStyles;
	private $adminStyles;
	private $publicScripts;
	private $adminScripts;
	private $phpClasses;
	private $phpFiles;
	private $namespaces = array();
	
	// Constants
	const PHP_CLASSES = 0;
	const PHP_FILES = 1;
	const JS = 2;
	const CSS = 3;
	
	/**
	 * Pretty print the file tree
	 */
	public function printFileTree() {
		print('<pre>').print_r($this->getFileTree(),true).print('<pre>');
	}

	/**
	 * Register the autoload function
	 */
	public function registerAutoloadFunc() {
		spl_autoload_register(array($this,'autoload'));
	}
	
	/**
	 * Add a new namespace to the namespace registry
	 * @param string $namespace
	 */
	public function registerNamespace($namespace) {
		$this->namespaces[] = $namespace;
		// Remove duplicates
		$this->namespaces = array_unique($this->namespaces);
	}
	
	/**
	 * The class autoload function
	 */
	public function autoload($class) {
		$class = ltrim($class, '\\');
		$files = $this->getFilesByExt(self::PHP_CLASSES);
		
		// Verify that the class is in the supported namespaces
		$namespaceSupported = false;
		foreach($this->namespaces as $namespace)
			if(strpos($class, $namespace) === 0) {
				$namespaceSupported = true;
				// Remove the namespace from the class' name
				$class = str_replace($namespace . '\\', '', $class);
			}
		// Namespace unsupported
		if(!$namespaceSupported) return;
		
		// Look for class' file
		foreach($files as $file)
			if($file['name'] === $class)
				$path = $file['path'];
		
		// File not found
		if(!file_exists($path)) {
			echo 'The following class could not be loaded: ' . $class . ' ';
			echo 'Path: ' . $path;
		}
		
		// Include class
		else require_once($path);
	}
	
	/**
	 * Get admin-facing styles
	 * 
	 * Returns an array holding the headers of the admin-facing
	 * stylesheets, inluding their path, dependencies and url.
	 * @return	array	The stylsheet headers
	 */
	public function getAdminStyles() {
		if(!isset($this->adminStyles)) {
			$this->adminStyles = $this->getFilesByExtAcc(self::CSS, 'admin');
		}
		return $this->adminStyles;
	}
	
	/**
	 * Get admin-facing scripts
	 * 
	 * Returns an array holding the headers of the admin-facing
	 * JavaScript files, inluding their path, dependencies and url.
	 * @return	array	The script headers
	 */
	public function getAdminScripts() {
		if(!isset($this->adminScripts)) {
			$this->adminScripts = $this->getFilesByExtAcc(self::JS, 'admin');
		}
		return $this->adminScripts;
	}
	
	/**
	 * Get public-facing styles
	 * 
	 * Returns an array holding the headers of the public-facing
	 * stylesheets, inluding their path, dependencies and url.
	 * @return	array	The stylsheet headers
	 */
	public function getPublicStyles() {
		if(!isset($this->publicStyles)) {
			$this->publicStyles = $this->getFilesByExtAcc(self::CSS, 'public');
		}
		return $this->publicStyles;
	}
	
	/**
	 * Get public-facing scripts
	 * 
	 * Returns an array holding the headers of the public-facing
	 * JavaScript files, inluding their path, dependencies and url.
	 * @return	array	The script headers
	 */
	public function getPublicScripts() {
		if(!isset($this->publicScripts)) {
			$this->publicScripts = $this->getFilesByExtAcc(self::JS, 'public');
		}
		return $this->publicScripts;
	}
	
	/**
	 * Get the portion of the file tree that
	 * holds all the files that end with $ext
	 * @param	type	$ext	The file extension
	 * @return	array			The file tree
	 */
	public function getFilesByExt($ext) {
		$fileTree = $this->getFileTree();
		switch($ext) {
			case self::PHP_FILES:
				return $fileTree['php']['files'];
				break;
			case self::PHP_CLASSES:
				return $fileTree['php']['classes'];
				break;
			case self::CSS:
				return $fileTree['css'];
				break;
			case self::JS:
				return $fileTree['js'];
				break;
		}
	}
	
	/**
	 * Get files by access and extension
	 * @param type $ext
	 * @param type $access
	 * @return type
	 */
	public function getFilesByExtAcc($ext, $access) {
		$files = $this->getFilesByExt($ext);
		$results = array();
		foreach($files as $file)
			if($file['facing'] === $access)
				$results[] = $file;
		return $results;
	}
	
	/**
	 * Get files by access
	 * @param type $access
	 * @return type
	 */
	public function getFilesByAcc($access) {
		$files = $this->getFileTree();
		$results = array();
		foreach($files as $file)
			if($file['facing'] === $access)
				$results[] = $file;
		return $results;
	}
	
	/**
	 * List all files in directories and subdirectories
	 * according to their type
	 * @return	array	the file tree arranged by file
	 *					types
	 */
	public function getFileTree() {
		
		// Lazy instantiation
		if(!isset($this->fileTree)) {
			
			$di = new \RecursiveDirectoryIterator(PLUGIN_DIR,\RecursiveDirectoryIterator::SKIP_DOTS);
			$it = new \RecursiveIteratorIterator($di);
			
			// Each file
			$files = array();
			foreach($it as $file) {
				
				// Properties
				$fileinfo = pathinfo($file);
				$path = $file->getPathname();
				$url = plugin_dir_url(
					str_replace(
						dirname(PLUGIN_DIR), 
						'', 
						$file->getPathname()
					)
				) . $file->getFilename();
				$headers = $this->get_file_data(
					$file, 
					array(
						'facing' => 'facing',
						'version' => 'version',
						'dependencies' => 'depends'
					)
				);
				
				
				switch($fileinfo['extension']) {
					
					// PHP files
					case 'php':
						// PHP classes (file.class.php)
						if(substr($fileinfo['filename'], -6) === '.class') {
							$files['php']['classes'][] = array(
								'name' => str_replace('.class', '', $fileinfo['filename']),
								'path' => $file->getPathname()
							);
						}
						else {
							$files['php']['files'][] = array(
								'name' => $fileinfo['filename'],
								'path' => $path
							);
						}
						break;
					
					// JavaScript files
					case 'js':
						$files['js'][] = array(
							'name' => $fileinfo['filename'],
							'path' => $path,
							'url' => $url,
						) + $headers;
						break;
					
					// CSS files
					case 'css':
						$files['css'][] = array(
							'name' => $fileinfo['filename'],
							'path' => $path,
							'url' => $url,
						) + $headers;
						break;
				}
			}
			$this->fileTree = $files;
		}
		return $this->fileTree;
	}
	
	/**
	 * Retrieve metadata from a file.
	 *
	 * This function is a slightly modified version of the same function that
	 * is a part of the WordPress core functions. 
	 * Searches for metadata in the first 1kiB of a file, such as a plugin or theme.
	 * Each piece of metadata must be on its own line. Fields can not span multiple
	 * lines, the value will get cut at the end of the first line.
	 *
	 * If the file data is not within that first 1kiB, then the author should correct
	 * their plugin file and move the data headers to the top.
	 *
	 * @see http://codex.wordpress.org/File_Header
	 * @see https://core.trac.wordpress.org/browser/tags/3.8.1/src/wp-includes/functions.php
	 *
	 * @since 1.2
	 * @param string $file Path to the file
	 * @param array $default_headers List of headers, in the format array('HeaderKey' => 'Header Name')
	 * @param string $context If specified adds filter hook "extra_{$context}_headers"
	 */
	function get_file_data( $file, $default_headers, $context = '' ) {
		// We don't need to write to the file, so just open for reading.
		$fp = fopen( $file, 'r' );

		// Pull only the first 1kiB of the file in.
		$file_data = fread( $fp, 1024 );

		// PHP will close file handle, but we are good citizens.
		fclose( $fp );

		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );

		if ( $context && $extra_headers = apply_filters( "extra_{$context}_headers", array() ) ) {
			$extra_headers = array_combine( $extra_headers, $extra_headers ); // keys equal values
			$all_headers = array_merge( $extra_headers, (array) $default_headers );
		} else {
			$all_headers = $default_headers;
		}

		foreach ( $all_headers as $field => $regex ) {
			if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] ) {
				$value = _cleanup_header_comment( $match[1] );
								
				// Parse arrays
				if(preg_match('/{([^}]*)}/', $value, $arr))
					$all_headers[ $field ] = explode(',', $arr[1]);
				else $all_headers[ $field ] = $value;
				
			}
					
			else
				$all_headers[ $field ] = '';
		}

		return $all_headers;
	}
	
	/**
	 * Localize a script
	 * 
	 * This is a slightly different version of the same function that is a part
	 * of the WordPress core functions.
	 * 
	 * @global	type	$wp_scripts
	 * @param	string	$handle
	 * @param	string	$object_name
	 * @param	array	$data		The data itself. The data can be either a single or multi-dimensional array.
	 * @return	boolean				True if the script was successfully localized, false otherwise.
	 */
	function localizeScript( $handle, $object_name, $data ) {
	        global $wp_scripts;
	        return $wp_scripts->localize( $handle, $object_name, $data );
	}
}