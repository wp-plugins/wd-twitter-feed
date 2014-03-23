<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*--------------------------------------------------*\
 * Twitter Feed Widget class
 * 
 * This class allows the user to add a twitter feed
 * widget to his Wordpress blog to display tweets from
 * any Twitter account. This class supports multiwidget.
\*--------------------------------------------------*/

class AskupaTwitterFeedWidget extends Widget {

	/**
	 * Constructor
	 *
	 * Specifies the classname and description, instantiates
	 * the widget, loads localization files, and includes
	 * necessary stylesheets and JavaScript.
	 */
	public function __construct() {		$config = array(
			'version' => PLUGIN_VERSION,
			'name' => 'Twitter Feed Widget',
			'languages-url' => PLUGIN_DIR . 'languages/',
			'description' => 'A simple and powerful Twitter feed widget',
			'form' => array(
				'widgetappearance' => array(
					'type' => 'hr',
					'label' => 'Widget Appearance'
				),
				'title' => array(
					'type' => 'text',
					'label' => 'Widget Title',
					'description' => 'Set the widget\'s title',
					'default' => 'My Tweets'
				),
				'wrapper' => array(
					'type' => 'checkbox',
					'label' => 'Display Widget Wrapper',
					'default' => true
				),
				'query' => array(
					'type' => 'hr',
					'label' => 'Query'
				),
				'resource' => array(
					'type' => 'dropdown',
					'label' => 'Resource',
					'description' => 'Choose the twitter resource type',
					'default' => 'User Timeline',
					'options' => array(
						'User Timeline' => 'usertimeline',
						'Home Timeline' => 'hometimeline',
						'Mentions Timeline' => 'mentionstimeline',
						'Retweets of Me' => 'retweetsofme',
						'List' => 'list',
						'Search' => 'search'
					)
				),
				'user' => array(
					'type' => 'text',
					'label' => 'User Name',
					'description' => 'Specify the Twitter user name of which the tweets will be shown',
					'default' => 'nasa',
					'after' => '<input type="text" disabled="disabled" value="Please enter a valid user name above" class="widefat user-validator" />'
				),
				'list' => array(
					'type' => 'text',
					'label' => 'List Name',
					'description' => 'Specify the Twitter user name of which the tweets will be shown',
					'default' => ''
				),
				'search' => array(
					'type' => 'text',
					'label' => 'Search Query',
					'description' => 'Specify the Twitter user name of which the tweets will be shown',
					'default' => 'from:SomeUser OR #hashtag'
				),
				'tweets' => array(
					'type' => 'number',
					'label' => 'Number of Tweets',
					'description' => 'Set the number of tweets',
					'default' => 5,
					'min' => 1,
					'max' => 800
				),
				'replies' => array(
					'type' => 'checkbox',
					'label' => 'Show Replies',
					'default' => true
				),
				'retweets' => array(
					'type' => 'checkbox',
					'label' => 'Show Retweets',
					'default' => true
				),
				'style' => array(
					'type' => 'hr',
					'label' => 'Tweet Styling'
				),
				'dir' => array(
					'type' => 'dropdown',
					'label' => 'Tweet Direction',
					'default' => 'ltr',
					'options' => array(
						'LTR' => 'ltr',
						'RTL' => 'rtl'
					)
				),
				'skin' => array(
					'type' => 'dropdown',
					'label' => 'Tweet Skin',
					'default' => 'default-skin',
					'options' => array(
						'Default' => 'default-skin',
						'Simplistic' => 'simplistic-skin',
						'Futuristic' => 'futuristic-skin',
						'Talk Bubble' => 'talk-bubble-skin',
					)
				),
				'assets' => array(
					'type' => 'hr',
					'label' => 'Tweet Assets'
				),
				'avatar' => array(
					'type' => 'checkbox',
					'label' => 'Show Avatar',
					'default' => true
				),
				'tweetactions' => array(
					'type' => 'checkbox',
					'label' => 'Show Tweet Actions',
					'default' => true
				),
				'tweettime' => array(
					'type' => 'checkbox',
					'label' => 'Show Date & Time',
					'default' => true
				),
				'screenname' => array(
					'type' => 'checkbox',
					'label' => 'Show Screen Name',
					'default' => true
				),
				'username' => array(
					'type' => 'checkbox',
					'label' => 'Show User Name',
					'default' => true
				)
			)
		);		parent::init($config);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param	array	args		The array of form elements
	 * @param	array	instance	The current instance of the widget
	 */
	public function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		$title = apply_filters('widget_title', $instance['title']);		if ($instance['wrapper'])
			echo $before_widget;

		if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];		$parser = TweetsParser::get_instance();
		try {
			$tweets = $parser->getTweets(array(
				'resource' => $instance['resource'],
				'user' => $instance['user'],
				'list' => $instance['list'],
				'query' => $instance['search'],
				'count' => $instance['tweets'],
				'replies' => $instance['replies'],
				'retweets' => $instance['retweets']
			));		} catch (\Exception $e) {
			echo '<div class="askupa-twitter-error"><strong>Error:</strong> ' . $e->getMessage() . '</div>';
			echo ( $instance['wrapper'] ? $after_widget : '' );
			return;
		}		$tweetview = new StaticTweetsView($tweets, $instance);		echo $tweetview->display();		echo ( $instance['wrapper'] ? $after_widget : '' );
	}

	/*--------------------------------------------------*\
	 * Activation and deactivation hooks
	\*--------------------------------------------------*/
	
	/**
	 * Fired when the widget is activated
	 */
	public function activate($network_wide) {}

	/**
	 * Fired when the widget is deactivated
	 */
	public function deactivate($network_wide) {		delete_option('widget_' . $this->get_classname());
	}
	
	/*--------------------------------------------------*\
	 * Script and stylesheet registration
	\*--------------------------------------------------*/
	
	/**
	 * Registers and enqueues 
	 * public-facing JavaScript.
	 */
	public function register_widget_styles() {}

	/**
	 * Registers and enqueues 
	 * public-facing JavaScript.
	 */
	public function register_widget_scripts() {}
}
