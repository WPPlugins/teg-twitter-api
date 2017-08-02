<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG Twitter API TEG_TA_AJAX.
 *
 * AJAX Event Handler.
 *
 * @class    TEG_TA_AJAX
 * @package  TEG_Twitter_Api/Classes
 * @category Class
 * @author   ThemeEgg
 */
class TEG_TA_AJAX
{

    /**
     * Hook in ajax handlers.
     */
    public static function init()
    {
        self::add_ajax_events();
    }

    /**
     * Hook in methods - uses WordPress ajax handlers (admin-ajax).
     */
    public static function add_ajax_events()
    {
        $ajax_events = array(
            'test' => false,
        );

        foreach ($ajax_events as $ajax_event => $nopriv) {
            add_action('wp_ajax_teg_twitter_api_' . $ajax_event, array(__CLASS__, $ajax_event));

            if ($nopriv) {
                add_action('wp_ajax_nopriv_teg_twitter_api_' . $ajax_event, array(__CLASS__, $ajax_event));

            }
        }
    }

    /**
     */
    public static function test()
    {

    }
}

TEG_TA_AJAX::init();
