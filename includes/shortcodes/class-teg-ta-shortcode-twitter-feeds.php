<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Twitter Shortcodes
 *
 * Used to show twitter trends, tweets etc.
 *
 * @author        ThemeEgg
 * @category    Shortcodes
 * @package    TEG_TA_Twitter_API/Shortcodes/Twitter
 * @version     1.0.0
 */
class TEG_TA_Shortcode_Twitter_Feeds implements TEG_TA_Shortcode_Interface
{


    /**
     * Output the cart shortcode.
     *
     * @param array $atts
     */
    public static function output($atts = array())
    {

        $defaultAttr = array(
            'count' => 5,
            'title' => __('Twitter Tweets', 'teg-twitter-api'),
        );

        $attributes = wp_parse_args($atts, $defaultAttr);

        $twitterObj = new TEG_TA_Api_Twitter_Tweets();


        $twitter_feed_array = $twitterObj->getTweets($attributes['count']);

        if (gettype($twitter_feed_array) !== 'array') {

            $twitter_feed_array = array();
        }

        $data = array(

            'twitter_feeds_array' => $twitter_feed_array,

            'twitter_username' => get_option('teg_twitter_api_twitter_username'),

            'title' => $attributes['title']
        );

        teg_ta_get_template('shortcodes/content-shortcode-twitter-feeds.php', $data);


    }
}
