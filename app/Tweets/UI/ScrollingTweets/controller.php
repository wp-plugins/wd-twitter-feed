<?php

/**
 * @package   Twitter Feed
 * @date      Mon Feb 02 2015 21:18:40
 * @version   2.0.0
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Tweets\UI;

/**
 * Implements a scrolling tweet list controller.
 */
class ScrollingTweets extends \TwitterFeed\Tweets\AbstractTweet 
{
    public function get_defaults() 
    {
        return array(
            'scroll_time'   => 10,
            'skin'          => 'simplistic'
        );
    }
}