<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * TEG_TA_Shortcodes class
 *
 * @class       TEG_TA_Shortcodes
 * @version     1.0.0
 * @package     TEG_Twitter_Api/Classes
 * @category    Class
 * @author      ThemeEgg
 */
class TEG_TA_Shortcodes
{

    /**
     * Init shortcodes.
     */
    public static function init()
    {
        $shortcodes = array(

            'twitter_feeds' => __CLASS__ . '::twitter_feeds',
            'twitter_trends' => __CLASS__ . '::twitter_trends',

        );

        foreach ($shortcodes as $shortcode => $function) {
            add_shortcode(apply_filters("{$shortcode}_shortcode_tag", $shortcode), $function);
        }


    }

    /**
     * Shortcode Wrapper.
     *
     * @param string[] $function
     * @param array $atts (default: array())
     * @param array $wrapper
     *
     * @return string
     */
    public static function shortcode_wrapper(
        $function,
        $atts = array(),
        $wrapper = array(
            'class' => 'teg-twitter-api',
            'before' => null,
            'after' => null,
        )
    )
    {
        ob_start();

        echo empty($wrapper['before']) ? '<div class="' . esc_attr($wrapper['class']) . '">' : $wrapper['before'];
        call_user_func($function, $atts);
        echo empty($wrapper['after']) ? '</div>' : $wrapper['after'];

        return ob_get_clean();
    }

    /**
     * Twitter tweets shortcodes
     *
     * @param mixed $atts
     * @return string
     */
    public static function twitter_feeds($atts)
    {
        return self::shortcode_wrapper(array('TEG_TA_Shortcode_Twitter_Feeds', 'output'), $atts);
    }

    /**
     * Twitter trends shortcodes
     *
     * @param mixed $atts
     * @return string
     */
    public static function twitter_trends($atts)
    {
        return self::shortcode_wrapper(array('TEG_TA_Shortcode_Twitter_Trends', 'output'), $atts);
    }
}
