<?php
/**
 * @package    twitterfeed
 * @date       Tue Jul 28 2015 14:02:04
 * @version    2.0.8
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
