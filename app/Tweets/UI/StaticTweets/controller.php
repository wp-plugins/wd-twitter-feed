<?php
/**
 * @package    twitterfeed
 * @date       Tue Jul 28 2015 14:02:04
 * @version    2.0.8
 * @author     Askupa Software <contact@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2014 Askupa Software
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
            'direction' => 'ltr',
            'show'      => array()
        );
    }
}
