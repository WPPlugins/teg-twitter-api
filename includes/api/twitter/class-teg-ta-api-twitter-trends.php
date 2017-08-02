<?php

class TEG_TA_Api_Twitter_Trends
{

    public $url = 'https://api.twitter.com/1.1/trends/place.json';

    public $availableUrl = 'GET https://api.twitter.com/1.1/trends/available.json';

    public $getfield = '?id=1';

    public $requestMethod = 'GET';

    public $error_message = '';


    function __construct()
    {

        defined('ABSPATH') or exit;

    }

    public function setGetField($locationID)
    {

        $this->getfield = '?id=' . $locationID;

    }

    public function getGetField()
    {

        return $this->getfield;

    }


    public function setAvailableUrl($availableUrl)
    {

        $this->availableUrl = $availableUrl;

    }

    public function getAvailableUrl()
    {

        return $this->availableUrl;

    }

    public function setCurlUrl($curlUrl)
    {

        $this->url = $curlUrl;

    }

    public function getCurlUrl()
    {

        return $this->url;

    }

    public function getTrends()
    {

        $twitterSettings = new TEG_TA_Api_Lib_Settings();

        $twitter = new TEG_TA_Api_Lib_Twitter_Api_Exchange($twitterSettings->getTwitterSettings());


        $trends = $twitter->setGetfield($this->getfield)->buildOauth($this->url, $this->requestMethod)->performRequest();

        $trends_array = json_decode($trends, true);


        if (isset($trends_array['errors'])) {

            $this->error_message = __('Please check your necessary twitter auth keys.', 'teg-twitter-api');

            if (isset($trends_array['errors'][0]['message'])) {

                $this->error_message .= '<br/>' . $trends_array['errors'][0]['message'];
            }

            add_action('teg_ta_twitter_trend_shortcode_layout_after', array($this, 'error_message'), 10, 0);

            add_action('teg_ta_twitter_trend_widget_layout_after', array($this, 'error_message'), 10, 0);

            return array();

        }

        $trends_array = isset($trends_array[0]) && isset($trends_array[0]['trends']) ? $trends_array[0]['trends'] : array();

        return $trends_array;

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