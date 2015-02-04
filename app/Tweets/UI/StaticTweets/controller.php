<?php

/**
 * @package   Twitter Feed
 * @date      Tue Feb 03 2015 18:42:58
 * @version   2.0.1
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
            'show'      => array(
                'time',
                'avatar',
                'username',
                'screenname',
                'actions',
                'retweets',
                'replies',
                'media'
            )
        );
    }
}
