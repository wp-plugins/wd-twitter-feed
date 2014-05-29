<?php

namespace TWITTERFEED;

/*------------------------------------------------------------*/
/* Tokens
/*------------------------------------------------------------*/
$sections[] = array (
	'label' => 'Tokens',
	'icon' => 'fa-list-ol',
	'description' => 'Set your Twitter account credentials. This is used to make the query to Twitter.com using the new API 1.1',
	'fields' => array(
		'oauth_access_token' => array(
			'type' => 'Textfield',
			'label' => 'Access Token',
			'description' => '',
			'default' => ''
		),
		'oauth_access_token_secret' => array(
			'type' => 'Textfield',
			'label' => 'Access Token Secret',
			'description' => '',
			'default' => ''
		),
		'consumer_key' => array(
			'type' => 'Textfield',
			'label' => 'API Key',
			'description' => '',
			'default' => ''
		),
		'consumer_secret' => array(
			'type' => 'Textfield',
			'label' => 'API Secret',
			'description' => '',
			'default' => ''
		)
	)
);

/*------------------------------------------------------------*/
/* Caching & Performance
/*------------------------------------------------------------*/
$sections[] = array (
	'label' => 'Caching & Performance',
	'icon' => 'fa-dashboard',
	'fields' => array(
		'force_tweet_count' => array(
			'multi-field' => array(
				'enable' => array(
					'type' => 'OnOffSwitch',
					'default' => false,
					'field-description' => 'Twitter will not always return the requested number of tweets, since the tweet "count" parameter behaves more closely in concept to an "up to" parameter. Enable this option if you want to ensure that you get the right number of tweets. Notice that enabling this option can increase the network request time as multiple request will be made to Twitter.com until the requested count is returned. It is highly recommanded that you enable caching if you choose to enable this option.'
				),
				'request_limit' => array(
					'type' => 'Number',
					'default' => 3,
					'field-description' => 'set the maximum number of requests in case Twitter has returned a wrong number of tweets',
					'min' => 1,
					'max' => 5,
					'step' => 1
				)
			),
			'label' => 'Force Tweet Count',
			'description' => 'Make sure that the number of tweets recieved from Twitter.com matches the requested number of tweets',
		),
		'enable_caching' => array(
			'type' => 'OnOffSwitch',
			'label' => 'Enable Caching',
			'description' => 'Use this option if you want to avoid sending a query to Twitter.com on every page reload',
			'field-description' => 'This is useful if you have a high traffic website and you wish to avoid reaching Twitter\'s query limits. Note that this can dramatically decrese the time it takes the server to return a response, since the data will be fetched from the local database, rather than from Twitter.com which is a remote address. For more information about Twitter rate limits, see <a href="https://dev.twitter.com/docs/rate-limiting/1.1">twitter-rate-limiting</a>',
			'default' => false
		),
		'caching_freq' => array(
			'type' => 'Slider',
			'label' => 'Caching Frequency',
			'description' => 'Set the caching frequency in seconds',
			'field-description' => 'This is used as the minimum threshold to make a query to Twitter.com. In other words, this is the minimum time between each query',
			'default' => 25,
			'min' => 10,
			'max' => 3600,
			'step' => 5
		),
		'cache_data' => array(
			'type' => 'Textarea',
			'data_type' => 'php',
			'label' => 'Cache Data',
			'description' => 'Here you can view all of the stored data, if the caching option is turned on',
			'default' => '',
			'disabled' => true
		),
		'clear_cache' => array(
			'type' => 'Button',
			'label' => 'Clear Cache',
			'description' => 'Use this button to clear all the data currently stored in the cache',
			'function' => 'clear_cache',
			'id' => 'clear-cache'
		),
		// This field is used by the tweets parser
		'last_caching_time' => array(
			'type' => 'Hidden'
		)
	)
);

/*------------------------------------------------------------*/
/* How to use
/*------------------------------------------------------------*/
$sections[] = array (
	'label' => 'How to use',
	'icon' => 'fa-question-circle',
	'html' => $this->include_as_string( 'config/options/how-to-use.php' )
);

/*------------------------------------------------------------*/
/* About the plugin
/*------------------------------------------------------------*/
$sections[] = array (
	'label' => 'About the plugin',
	'icon' => 'fa-info-circle',
	'html' => $this->include_as_string( 'config/options/about-the-plugin.html' )
);

/*------------------------------------------------------------*/
/* Report a bug
/*------------------------------------------------------------*/
$sections[] = array (
	'label' => 'Report a bug',
	'icon' => 'fa-edit',
	'fields' => array(
		'error_message' => array(
			'type' => 'textarea',
			'label' => 'Explain the issue',
			'description' => 'Use this field to thoroughly explain the issue that you are having with the plugin. Try to provide as much detail as you can. Write down the error message that you are getting, as well as the console log, as these are important details that can help our team reproduce the error and fix it.',
			'default' => '',
			'force-default' => true
		),
		'debug_mode' => array(
			'type' => 'OnOffSwitch',
			'label' => 'Debug Mode',
			'default' => false,
			'description' => 'Enable/disable debug mode. This will display a debug window in the main website with useful information about the last run of the plugin.'
		),
		'error_report_button' => array(
			'type' => 'button',
			'label' => 'Send Report',
			'description' => '',
			'id' => 'error-report-button',
			'function' => 'send_report'
		)
	)
);

/*------------------------------------------------------------*/
/* Buy Full Version
/*------------------------------------------------------------*/
if(_is_full_version(false,true)) {
	$sections[] = array (
		'label' => 'Purchase Full Version',
		'icon' => 'fa-shopping-cart',
		'html' => $this->include_as_string( 'config/options/purchase-full.php' )
	);
}

/*------------------------------------------------------------*/
/* Configuration
/*------------------------------------------------------------*/
$config = array(
	'title' => 'Twitter Feed Options',
	'subtitle' => 'Use this page to set your Twitter tokens, caching prefrences and other styling options',
	'version' => PLUGIN_VERSION,
	'wp-menu-item' => 'Twitter Feed',
	'wp-page-title' => 'Twitter Feed Settings',
	'plugin-slug' => PLUGIN_SLUG,
	'plugin-dir' => PLUGIN_DIR,
	'option_name' => 'twitter-feed-options', // TODO: remove this
	'header-icon-url' => PLUGIN_URL . 'assets/img/twitter-feed-logo-64x64.png',
	'footer-text' => 'Developed by Askupa Software&reg;',
	'footer-icon-url' => PLUGIN_URL . 'assets/img/logo-40x216.png',
	'sections' => $sections,
	'sidebar-structure' => _is_full_version(array(
		$sections[0],
		$sections[1],
		'divider',
		$sections[2],
		$sections[3],
		$sections[4]
	), array(
		$sections[0],
		$sections[1],
		'divider',
		$sections[2],
		$sections[3],
		$sections[4],
		$sections[5]
	))
);
		
