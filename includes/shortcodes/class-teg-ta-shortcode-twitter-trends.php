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
class TEG_TA_Shortcode_Twitter_Trends implements TEG_TA_Shortcode_Interface
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
            'title' => __('Twitter Trends', 'teg-twitter-api'),
            'trends_WOEID' => 1
        );

        $attributes = wp_parse_args($atts, $defaultAttr);


        $twitterObj = new TEG_TA_Api_Twitter_Trends();

        if (is_numeric($attributes['trends_WOEID'])) {

            $twitterObj->setGetField($attributes['trends_WOEID']);
        }
        $twitterTrends = $twitterObj->getTrends();


        $data = array(

            'trends' => $twitterTrends,

            'number_of_trends' => $attributes['count'],

            'title' => $attributes['title']
        );

        teg_ta_get_template('shortcodes/content-shortcode-twitter-trends.php', $data);
    }
}
