<?php

class TEG_TA_Api_Twitter_Tweets
{

    public $requestMethod = "GET";

    public $count = 5;

    public $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

    public $error_message = '';

    function __construct()
    {

        if (!defined('ABSPATH')) {
            exit;
        }

    }

    protected function getField($count = null)
    {

        $twitterSettings = new TEG_TA_Api_Lib_Settings();

        if ((int)$count < 0) {

            $count = $this->count;

        }

        $getfield = "?screen_name=" . $twitterSettings->getTwitterUser() . "&count=$count";

        return $getfield;

    }

    public function getTweets($count = null)
    {
        $twitterSettings = new TEG_TA_Api_Lib_Settings();

        $twitter = new TEG_TA_Api_Lib_Twitter_Api_Exchange($twitterSettings->getTwitterSettings());

        $tweets = json_decode($twitter->setGetfield($this->getField($count))->buildOauth($this->url, $this->requestMethod)->performRequest(), true);


        if (isset($tweets['errors'])) {

            $this->error_message = __('Please check your necessary twitter auth keys.', 'teg-twitter-api');

            if (isset($tweets['errors'][0]['message'])) {

                $this->error_message .= '<br/>' . $tweets['errors'][0]['message'];
            }

            add_action('teg_ta_twitter_feed_shortcode_layout_after', array($this, 'error_message'), 10, 0);

            add_action('teg_ta_twitter_feed_widget_layout_after', array($this, 'error_message'), 10, 0);


            return array();

        }
//        echo '<pre>';
//        print_r($tweets);exit;

        return $tweets;

    }

    function error_message()
    {

        echo $this->error_message;

        $this->error_message = '';
    }

    function __destruct()
    {

    }

}