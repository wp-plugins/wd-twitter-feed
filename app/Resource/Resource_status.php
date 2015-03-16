<?php

/**
 * @package   Twitter Feed
 * @date      Mon Mar 16 2015 12:34:34
 * @version   2.0.3
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TwitterFeed\Resource;

/**
 * 
 */
class Resource_status 
{
    private $api;
    private $args;
    
    public function __construct( $args ) 
    {
        $this->api = new \j7mbo\TwitterAPIExchange($this->get_tokens());
        $this->args = $args;
    }
    
    public function perform_request()
    {
        return json_decode(
            $this->api->setGetfield($this->build_argument_list($this->args))
                 ->buildOauth($this->get_url(), 'GET')
                 ->performRequest()
            );
    }
    
    public function get_url() 
    {
        return TwitterResource::API_URL . TwitterResource::STATUS;
    }
    
    public function get_tokens()
    {
        if( !isset( $this->tokens ) )
        {
            global $twitterfeed_options;
            $this->tokens = array(
                'oauth_access_token'        => $twitterfeed_options['oauth_access_token'],
                'oauth_access_token_secret' => $twitterfeed_options['oauth_access_token_secret'],
                'consumer_key'              => $twitterfeed_options['consumer_key'],
                'consumer_secret'           => $twitterfeed_options['consumer_secret']
            );
        }
        return $this->tokens;
    }
    
    protected function build_argument_list(array $args) 
     {
        foreach($args as $key => $value)
        {
            $arg_list = add_query_arg( array( $key => $value ), $arg_list );
        }
        return $arg_list;
    }
}
