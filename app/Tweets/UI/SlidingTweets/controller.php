<?php

/**
 * @package   Twitter Feed
 * @date      Mon Apr 27 2015 18:06:42
 * @version   2.0.5
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Tweets\UI;

/**
 * Tweet List View
 * 
 * This class extends the TweetListView class and creates an html output to 
 * display a sliding tweet list. 
 */
class SlidingTweets extends StaticTweets
{
    public function get_defaults()
    {
        return parent::get_defaults() + array(
            'slide_dir'     => 'random',
            'slide_duration'=> 5
        );
    }
}