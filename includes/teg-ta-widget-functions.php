<?php
/**
 * TEG Twitter API Widget Functions
 *
 * Widget related functions and widget registration.
 *
 * @author        ThemeEgg
 * @category    Core
 * @package    TEGTwitterAPI/Functions
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include widget classes.
include_once(dirname(__FILE__) . '/abstracts/abstract-teg-ta-widget.php');
include_once(dirname(__FILE__) . '/widgets/class-teg-ta-widget-twitter-trends.php');
include_once(dirname(__FILE__) . '/widgets/class-teg-ta-widget-twitter-feeds.php');


/**
 * Register Widgets.
 *
 * @since 1.0.0
 */
function teg_ta_register_widgets()
{

    register_widget('TEG_TA_Widget_Twitter_Trends');
    register_widget('TEG_TA_Widget_Twitter_Feeds');

}

add_action('widgets_init', 'teg_ta_register_widgets');
