<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * This is the configuration file for the popup window that is shown when 
 * clicking on the Twitter Feed shortcode button (in the rich text editor)
\*----------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------*/
/*	Common Fields
/*----------------------------------------------------------------------------*/
$_dir = array(
	'type' => 'dropdown',
	'label' => 'Direction',
	'description' => 'Choose the direction of the tweets. RTL is good for languages that are written from right to left, such as Hebrew.',
	'default' => 'LTR',
	'options' => array(
		'LTR' => 'ltr',
		'RTL' => 'rtl'
	)
);
$_user = array(
	'type' => 'text',
	'label' => 'User Name',
	'description' => 'This field is only applicable if the twitter resource type is set to "User Timeline" or "List". Enter the Twitter accout username of which you would like to display the tweets',
	'default' => ''
);
$_list = array(
	'type' => 'text',
	'label' => 'List Name',
	'description' => 'This field is only applicable if the twitter resource type is set to "List". Enter the list\'s name here. ',
	'default' => ''
);
$_query = array(
	'type' => 'text',
	'label' => 'Search Query',
	'description' => 'This field is only applicable if the twitter resource type is set to "Search". Enter the search query here. For more information about the query syntax, visit Twitter\'s <a target="_blank" href="https://dev.twitter.com/docs/using-search">search guide</a>',
	'default' => 'from:username AND #hashtag OR @username'
);
$_resource = array(
	'type' => 'dropdown',
	'label' => 'Resource',
	'description' => 'Choose the twitter resource type',
	'default' => 'User Timeline',
	'options' => array(
		'User Timeline' => 'usertimeline'
	) + _is_full_version(array(
		'Home Timeline' => 'hometimeline',
		'Mentions Timeline' => 'mentionstimeline',
		'Retweets of Me' => 'retweetsofme',
		'List' => 'list',
		'Search' => 'search'
	),array())
);
$_tweets = array(
	'type' => 'number',
	'label' => 'Tweets',
	'description' => 'Choose the number of tweets to display',
	'min' => 1,
	'max' => _is_full_version(800, 20),
	'default' => 5,
);

/* Assets */
$_assets['slidingtweets'] = array(
	'type' => 'checkbox',
	'label' => 'Show options',
	'description' => 'Select which tweet assets you would like to show',
	'options' => array(
		'User Name' => true,
		'Screen Name' => true,
		'Avatar' => true,
		'Tweet Time' => true,
		'Tweet Actions' => true,
		'Replies' => true,
		'Retweets' => true
	)
);
$_assets['statictweets'] = $_assets['slidingtweets'];
$_assets['statictweets']['options']['media'] = true; // Add media to static tweets

/* skin */
$_skin['statictweets'] = array(
	'type' => 'dropdown',
	'label' => 'Skin',
	'description' => 'Choose how to skin the tweets',
	'default' => 'Default',
	'options' => array(
		'Default' => 'default-skin',
		'Simplistic' => 'simplistic-skin'
	) + _is_full_version(array(
		'Futuristic' => 'futuristic-skin',
		'Talk Bubble' => 'talk-bubble-skin'
	), array())
);
$_skin['scrollingtweets'] = array(
	'type' => 'dropdown',
	'label' => 'Skin',
	'description' => 'Choose how to skin the tweets. Note: for the LED Screen skin, use a longer scrolling time since the font size is bigger and it takes longer to scroll through the tweet',
	'default' => 'Default',
	'options' => array(
		'Default' => 'default-skin',
		'LED Screen' => 'led-screen-skin'
	)
);
$_skin['slidingtweets'] = $_skin['statictweets'];

/**
 * This field will be returned for unsupported fields
 */
if(!function_exists(__namespace__.'\not_supported')) {
	function not_supported($field) {
		return array(
			'type' => 'blank',
			'label' => $field['label'],
			'text' => 'The '.$field['label'].' option is not available in this version',
			'description' => $field['description']
		);
	}
}

/*----------------------------------------------------------------------------*/
/*	Static Tweets
/*----------------------------------------------------------------------------*/
$config['statictweets'] = array(
	'tinymce-label' => 'Static Tweets',
	'tinymce-disabled' => false,
	'param' => array(
		'dir' => $_dir,
		'skin' => $_skin['statictweets'],
		'resource' => $_resource,
		'user' => $_user,
		'list' => isset($_resource['options']['List']) ? $_list : not_supported($_list),
		'query' => isset($_resource['options']['Search']) ? $_query : not_supported($_query),
		'tweets' => $_tweets,
		'show' => $_assets['statictweets']
	),
	'shortcode' => '[statictweets skin="{skin}" dir="{dir}" resource="{resource}" user="{user}" list="{list}" query="{query}" count="{tweets}" show="username:{username},screenname:{screenname},avatar:{avatar},tweettime:{tweettime},tweetactions:{tweetactions},replies:{replies},retweets:{retweets},media:{media}"/]',
);

/*----------------------------------------------------------------------------*/
/*	Scrolling Tweets
/*----------------------------------------------------------------------------*/
$config['scrollingtweets'] = array(
	'tinymce-label' => 'Scrolling Tweets',
	'tinymce-disabled' => _is_full_version(false, true),
	'param' => array(
		'scroll_time' => array(
			'type' => 'number',
			'label' => 'Scrolling Time',
			'description' => 'Set the duration of each slide in seconds',
			'min' => 5,
			'max' => 120,
			'step' => 1,
			'default' => 10
		),
		'skin' => $_skin['scrollingtweets'],
		'resource' => $_resource,
		'user' => $_user,
		'list' => $_list,
		'query' => $_query,
		'tweets' => $_tweets
	),
	'shortcode' => '[scrollingtweets scroll_time="{scroll_time}" skin="{skin}" resource="{resource}" user="{user}" list="{list}" query="{query}" count="{tweets}"/]'
);

/*----------------------------------------------------------------------------*/
/*	Sliding Tweet List
/*----------------------------------------------------------------------------*/
$config['slidingtweets'] = array(
	'tinymce-label' => 'Sliding Tweets',
	'tinymce-disabled' => _is_full_version(false, true),
	'param' => array(
		'slide_dir' => array(
			'type' => 'dropdown',
			'label' => 'Slide Direction',
			'description' => 'Set the direction of the slide (Up, Down, Left, Right)',
			'default' => 'Right',
			'options' => array(
				'Up' => 'up',
				'Down' => 'down',
				'Left' => 'left',
				'Right' => 'right',
				'Random' => 'random'
			)
		),
		'slide_duration' => array(
			'type' => 'number',
			'label' => 'Slide Duration',
			'description' => 'Set the duration of each slide in seconds',
			'min' => 2,
			'max' => 120,
			'step' => 1,
			'default' => 5
		),
		'skin' => $_skin['slidingtweets'],
		'resource' => $_resource,
		'user' => $_user,
		'list' => $_list,
		'query' => $_query,
		'tweets' => $_tweets,
		'show' => $_assets['slidingtweets']
		
	),
	'shortcode' => '[slidingtweets slide_dir="{slide_dir}" slide_duration="{slide_duration}" skin="{skin}" resource="{resource}" user="{user}" list="{list}" query="{query}" count="{tweets}" show="username:{username},screenname:{screenname},avatar:{avatar},tweettime:{tweettime},tweetactions:{tweetactions},replies:{replies},retweets:{retweets}" /]'
);