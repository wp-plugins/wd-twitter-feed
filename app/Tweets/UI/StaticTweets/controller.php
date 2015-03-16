<?php

/**
 * @package   Twitter Feed
 * @date      Mon Mar 16 2015 12:34:34
 * @version   2.0.3
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Tweets\UI;

/**
 * Implements a static tweet list controller.
 */
class StaticTweets extends \TwitterFeed\Tweets\AbstractTweet 
{
    public function get_defaults()
    {
        return array(
            'skin'      => 'simplistic',
            'dir'       => 'ltr',
            'show'      => array()
        );
    }
}
