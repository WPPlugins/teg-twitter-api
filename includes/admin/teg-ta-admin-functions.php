<?php
/**
 * TEG_Twitter_API Admin Functions
 *
 * @author   ThemeEgg
 * @category Core
 * @package  TEG_Twitter_API/Admin/Functions
 * @version  1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get all TEG_Twitter_API screen ids.
 *
 * @return array
 */
function teg_ta_get_screen_ids()
{

    $teg_ta_screen_id = sanitize_title(__('TEG Twitter API', 'teg-twitter-api'));
    $screen_ids = array(
        'toplevel_page_' . $teg_ta_screen_id,
        //$teg_ta_screen_id . '_page_teg_ta-reports',
    );


    return apply_filters('teg_twitter_api_screen_ids', $screen_ids);
}


/**
 * Get current tab ID
 *
 * @return array
 */
function teg_ta_get_current_tab()
{

    $current_tab = isset($_GET['tab']) ? $_GET['tab'] : '';

    return apply_filters('teg_twitter_api_current_tab', $current_tab);
}

/**
 * Get current section
 *
 * @return array
 */
function teg_ta_get_current_section()
{

    $current_tab = isset($_GET['section']) ? $_GET['section'] : '';

    return apply_filters('teg_twitter_api_current_section', $current_tab);
}





