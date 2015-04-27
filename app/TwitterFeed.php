<?php

/**
 * @package   Twitter Feed
 * @date      Mon Apr 27 2015 18:06:42
 * @version   2.0.5
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed;

use Amarkal\Loaders;
use Amarkal\Extensions\WordPress\Plugin;
use Amarkal\Extensions\WordPress\Widget;
use Amarkal\Extensions\WordPress\Options;
use Amarkal\Extensions\WordPress\Editor;

class TwitterFeed extends Plugin\AbstractPlugin 
{
    private static $options;
    
    public function __construct() 
    {
        parent::__construct( dirname( __DIR__ ).'/bootstrap.php' );

        $this->generate_defines();
     
        $this->load_classes();
        
        // Register an options page
        self::$options = new Options\OptionsPage( include('configs/options/config.php') );
        self::$options->register();
        
        // Register a widget
        $this->widget = new Widget\Widget( include('configs/widget/config.php') );
        $this->widget->register();
        
        // Add a popup button to the rich editor
        Editor\Editor::add_button( include('configs/editor/config.php') );
        
        // Register shortcodes
        Shortcode::register();
        
        $this->register_assets();
        
//        add_filter( 'mce_css', array( __CLASS__, 'editor_css' ) );
    }
    
    public function generate_defines()
    {
        $basepath = dirname( __FILE__ );
        define( __NAMESPACE__.'\LIBRARIES_DIR', dirname( $basepath ).'/vendor/' );
        define( __NAMESPACE__.'\PLUGIN_DIR', $basepath );
        define( __NAMESPACE__.'\PLUGIN_URL', plugin_dir_url( $basepath ).'app/' );
        define( __NAMESPACE__.'\JS_URL', plugin_dir_url( $basepath ).'app/assets/js' );
        define( __NAMESPACE__.'\CSS_URL', plugin_dir_url( $basepath ).'app/assets/css' );
        define( __NAMESPACE__.'\IMG_URL', plugin_dir_url( $basepath ).'app/assets/img' );
        define( __NAMESPACE__.'\PLUGIN_VERSION', '2.0.5' );
        define( __NAMESPACE__.'\PLUGIN_VERSION_TYPE', 'Demo');
    }
    
    public function load_classes()
    {
        $loader = new Loaders\ClassLoader();
        $loader->register_namespace( __NAMESPACE__, PLUGIN_DIR );
        
        // Special autoloader filter for \Tweet\UI
        $loader->register_autoload_filter( __NAMESPACE__, function( $class, $namespace, $dir )
        {
            if( strpos( $class, __NAMESPACE__."\Tweets\UI" ) === 0 )
            {
                $class .= '/controller';
                return $dir.str_replace(
                    array('\\',$namespace,$class), 
                    array(DIRECTORY_SEPARATOR,'',''), 
                    $class
                ).'.php';
            }
        });
        $loader->register();
        
        // Include core functions
        require_once PLUGIN_DIR.'/functions.php';
        
        // Include TwitterAPIExchange
        require_once LIBRARIES_DIR.'j7mbo/twitter-api-php/TwitterAPIExchange.php';
    }
    
    public function register_assets()
    {
        $al = new Loaders\AssetLoader();
        $al->register_assets(array(
                new \Amarkal\Assets\Script(array(
                    'handle'        => 'twitterfeed-script',
                    'url'           => JS_URL.'/twitter-feed.min.js',
                    'facing'        => array( 'public' ),
                    'version'       => PLUGIN_VERSION,
                    'dependencies'  => array('jquery'),
                    'footer'        => true
                )),
                new \Amarkal\Assets\Stylesheet(array(
                    'handle'        => 'twitterfeed-style',
                    'url'           => CSS_URL.'/twitter-feed.min.css',
                    'facing'        => array( 'public' ),
                    'version'       => PLUGIN_VERSION
                )),
                new \Amarkal\Assets\Stylesheet(array(
                    'handle'        => 'font-awesome',
                    'url'           => '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css',
                    'facing'        => array( 'public' ),
                    'version'       => '4.2.0'
                ))
            )
        );
        $al->enqueue();
        
        // Custom CSS
        add_action( 'wp_head', array( __CLASS__, 'custom_css' ) );
    }
    
    static function custom_css()
    {
        if( 'ON' == self::$options->get('css_toggle') )
        {
            $css = self::$options->get('css');
            echo "<style>$css</style>";
        }
    }
    
//    static function editor_css( $wp ) 
//    {
//        $wp .= ',' . CSS_URL.'/editor.min.css';
//        return $wp;
//    }
}
new TwitterFeed();