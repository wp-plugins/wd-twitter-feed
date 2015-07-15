<?php
/**
 * @package    twitterfeed
 * @date       Wed Jul 15 2015 19:28:35
 * @version    2.0.7
 * @author     Askupa Software <contact@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2014 Askupa Software
 */


$common = include( dirname( __DIR__ ).'/common.php' );

return new Amarkal\Extensions\WordPress\Editor\Plugin(array(
    'slug'      => 'twitterfeed_button',
    'row'       => 1,
    'script'    => TwitterFeed\JS_URL.'/editor.js',
    'callback'  => array(
        'statictweets'      => new Amarkal\Extensions\WordPress\Editor\FormCallback( $common['statictweets'] ),
        'scrollingtweets'   => new Amarkal\Extensions\WordPress\Editor\FormCallback( $common['scrollingtweets'] ),
        'slidingtweets'     => new Amarkal\Extensions\WordPress\Editor\FormCallback( $common['slidingtweets'] )
    )
));
