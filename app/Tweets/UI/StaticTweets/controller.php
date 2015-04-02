<?php

/**
 * @package   Twitter Feed
 * @date      Thu Apr 02 2015 00:53:18
 * @version   2.0.4
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
