=== Askupa Twitter Feed ===
Contributors: Askupa Software
Tags: twitter, tweet, tweets, feed, timeline, widget, customizable, comments, social, social media, shortcode, caching, 
Requires at least: 3.0
Tested up to: 3.8.0
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Powerful Twitter Integration System for WordPress

== Description ==

**Twitter Feed** plugin for WordPress allows you to easily display Twitter user timelines in a wide variety of ways, using the included *shortcode engine* and *widget*. The included skins are fully responsive. The plugin has the option of caching tweets to improve the performance of the plugin.

Visit the official [Twitter Feed](http://products.askupasoftware.com/twitter-feed/) page for live examples, code snippets and more information.

Follow us on [Twitter](http://www.twitter.com/askupasoftware) and on [Facebook](http://www.facebook.com/askupasoftware) to get the latest updates and version releases.

Try the [commercial version](http://codecanyon.net/item/twitter-feed-social-plugin-for-wordpress/6665168?ref=Askupa) of Twitter Feed that features 6 different twitter resources (including lists and search), 5 different skins and 3 different shortcodes!

**Feature List:**

1. Twitter API 1.1
1. Shortcodes + shortcode editor
1. Widget
1. LTR and RTL supoprt
1. Powerful Caching system
1. Tweet customization
1. Tweet actions
1. 100% responsive
1. Illustrated step-by-step guide
1. Clean & simple plugin options page
1. Very simple to setup

== Installation ==

1. Download the plugin zip package and extract it.
1. Put the folder named ???askupa-twitter-feed" under /wp-content/plugins/ directory
1. Goto the plugins page in your Wordpress admin panel and click "Activate"
1. Goto Plugins -> Twitter Feed and enter your oAuth credentials (you would need to create a new application at https://dev.twitter.com/apps)
1. You can implements tweets into your posts and pages using shortcodes or you can go to your widget area (Appearance -> widgets) and add *Twitter Feed* to your widget panel

== Frequently Asked Questions ==

= Why do I need to enable caching? =

Twitter Feed makes a request to the Twitter API every time someone views a page in your blog. This creates a problem since Twitter imposes a limit of 180 requests per hour. exceeding the limit would prevent you from making new requests until the clock is reset.
The caching system allows you to fetch the data locally, which not only will prevent you from exceeding the rate limits, but is also much faster than making a request to Twitter.

= Where can I update the settings? / I can't find the plugin settings =

In order to update the settings of the widget, you'll need to login to your Wordpress admin panel and navigate to *Plugins -> Twitter Feed* where you will find a complete options page for the plugin.

== Screenshots ==

1. The widget with 3 recent tweets.
2. When hovering over a tweet, tweeting option become visible.
3. The settings page allows you to insert your access tokens
4. To use the widget go to **Appearance -> Widgets** and add **WD Search Widget** widget.
5. This widget is specifically designed to work with both "ltr" and "rtl" languages.

== Changelog ==

= 1.2.1 =
* Filesystem cleanup
* Minor bug fixes

= 1.2.0 =
* Added support for the new Twitter API 1.1
* New feature - live username verification
* Minor CSS fixes 
* Improved error handling

= 1.1.0 =
* New feature - Change body and title colors using a color picker
* Minor CSS fixes 
* New ajax loader

= 1.0.1 =
* New feature - feed caching to avoid Twitter rate limiting
* Improved error handling
* Minor CSS fixes 
* Fixed bug - T_PAAMAYIM_NEKUDOTAYIM

= 1.0 =
* Initial plugin release

== Upgrade Notice ==

= 1.2.1 =
* Filesystem cleanup, minor bug fixes

= 1.2.0 =
* Added support for the new Twitter API 1.1

= 1.1.0 =
* New features and minor CSS fixes 

= 1.0.1 =
* Improved error handling and caching, minor bug fixes
* Hello World!