<?php

class TEG_TA_Api_Lib_Settings
{

    private $oauth_access_token;

    private $oauth_access_token_secret;

    private $consumer_key;

    private $consumer_secret;

    private $twitter_user;

    private $twitter_settings;

    function __construct()
    {

        defined('ABSPATH') or exit;

        $this->defaultCallback();

    }

    public function defaultCallback()
    {

        $this->setOauthAccessTtoken();

        $this->setOauthAccessTtokenSecret();

        $this->setConsumerKey();

        $this->setConsumerSecret();

        $this->setTwitterUser();

        $this->setTwitterSettings();

    }

    public function setOauthAccessTtoken()
    {

        $oauth_access_token = get_option('teg_twitter_api_twitter_oauth_access_token');

        $this->oauth_access_token = $oauth_access_token;

    }

    public function setOauthAccessTtokenSecret()
    {

        $oauth_access_token_secret = get_option('teg_twitter_api_twitter_oauth_token_secret');

        $this->oauth_access_token_secret = $oauth_access_token_secret;

    }

    public function setConsumerKey()
    {

        $consumer_key = get_option('teg_twitter_api_twitter_consumer_key');

        $this->consumer_key = $consumer_key;

    }

    public function setConsumerSecret()
    {
        $consumer_secret = get_option('teg_twitter_api_twitter_consumer_secret');


        $this->consumer_secret = $consumer_secret;

    }

    public function setTwitterUser()
    {

        $twitter_user = get_option('teg_twitter_api_twitter_username');

        $this->twitter_user = $twitter_user;

    }

    public function getOauthAccessTtoken()
    {

        return $this->oauth_access_token;

    }

    public function getOauthAccessTtokenSecret()
    {

        return $this->oauth_access_token_secret;

    }

    public function getConsumerKey()
    {

        return $this->consumer_key;

    }

    public function getConsumerSecret()
    {

        return $this->consumer_secret;

    }

    public function getTwitterUser()
    {

        return $this->twitter_user;

    }

    public function setTwitterSettings()
    {

        $settings = [
            'oauth_access_token' => $this->getOauthAccessTtoken(),
            'oauth_access_token_secret' => $this->getOauthAccessTtokenSecret(),
            'consumer_key' => $this->getConsumerKey(),
            'consumer_secret' => $this->getConsumerSecret(),
        ];

        $this->twitter_settings = $settings;
    }

    public function getTwitterSettings()
    {

        return $this->twitter_settings;

    }

    function _destruct()
    {

    }

}