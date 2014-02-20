=== Askupa Twitter Feed ===
Contributors: Askupa Software
Tags: twitter, tweet, tweets, feed, timeline, widget, ajax, customizable, comments, social, social media
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple yet powerful Twitter feed widget.

== Description ==

Integrate an amazing **Twitter Feed Widget** to your widget's sidebar! This plugin supports the new Twitter API 1.1

Try our commercial version of the product which features 6 different twitter resources (including lists and search), 5 different skins and 3 different shortcodes!
<a href=“http://codecanyon.net/item/twitter-feed-social-plugin-for-wordpress/6665168?ref=Askupa”>Askupa Twitter Feed</a>
<a href=“http://codecanyon.net/item/twitter-feed-social-plugin-for-wordpress/6665168?ref=Askupa”><img src="http://askupasoftware.com/wp-content/uploads/2014/02/box.png" alt="Askupa Software Twitter Feed plugin for WordPress"></a>

Feature List:

1. Supports the new Twitter API 1.1
1. Specifically designed to work with both "ltr" and "rtl" languages (see screenshots)
1. Allows data caching to avoid Twitter rate limits (180 per hour, using oAuth)
1. Graphically attractive, easily customizable, various styles are available
1. Very simple to setup


Follow me on twitter to be updated on new versions and releases
[@YoavKadosh](http://www.twitter.com/webdeskil)

Visit my website for more information about the plugin (written in Hebrew)
[WebDesk.co.il](http://webdesk.co.il/)

== Installation ==

1. Download the plugin zip package and extract it.
1. Put the folder named "wd-twitter-feed" under /wp-content/plugins/ directory
1. Goto the plugins page in your Wordpress admin panel and click "Activate"
1. Goto Settings -> Twitter Feed Tokens and enter your oAuth credentials (you would need to create a new application at https://dev.twitter.com/apps)
1. Goto your widget area (Appearance -> widgets) and add *WD Twitter Feed* to your widget panel

== Frequently Asked Questions ==

= What happens if I leave the caching frequency field blank? =

The  widget will make a request to the Twitter API every time you refresh the page. This is not recommended since Twitter imposes a limit of 180 requests per hour - exceeding the limit would prevent you from making new requests until the clock is reset.

= Where can I update the settings? / I can't find the plugin settings =

In order to update the settings of the widget, you'll need to login to your Wordpress admin panel and navigate to *Appearance -> Widgets* then choose *WD Twitter Feed* and drag it to the sidebar that you want to use it on.

= What about foo bar? =

That's a hard one, i'll have to get back to you on that.

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