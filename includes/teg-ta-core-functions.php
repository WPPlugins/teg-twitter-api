<?php
/**
 * TEG Twitter API Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author        ThemeEgg
 * @category    Core
 * @package    TEG_Twitter_Api/Functions
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}


include('teg-ta-formatting-functions.php');
include('teg-ta-widget-functions.php');


/**
 * Display a TEG Twitter API help tip.
 *
 * @since  1.0.0
 *
 * @param  string $tip Help tip text
 * @param  bool $allow_html Allow sanitized HTML if true or escape
 * @return string
 */
function teg_ta_help_tip($tip, $allow_html = false)
{
    if ($allow_html) {
        $tip = teg_ta_sanitize_tooltip($tip);
    } else {
        $tip = esc_attr($tip);
    }

    return '<span class="teg-twitter-api-help-tip" data-tip="' . $tip . '"></span>';
}

/**
 * Get template part (for templates like the shop-loop).
 *
 * TEG_TA_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
function teg_ta_get_template_part($slug, $name = '')
{
    $template = '';

    // Look in yourtheme/slug-name.php and yourtheme/teg-twitter-api/slug-name.php
    if ($name && !TEG_TA_TEMPLATE_DEBUG_MODE) {
        $template = locate_template(array("{$slug}-{$name}.php", TEGTApi()->template_path() . "{$slug}-{$name}.php"));
    }

    // Get default slug-name.php
    if (!$template && $name && file_exists(TEGTApi()->plugin_path() . "/templates/{$slug}-{$name}.php")) {
        $template = TEGTApi()->plugin_path() . "/templates/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/teg-tfwitter-api/slug.php
    if (!$template && !TEG_TA_TEMPLATE_DEBUG_MODE) {
        $template = locate_template(array("{$slug}.php", TEGTApi()->template_path() . "{$slug}.php"));
    }

    // Allow 3rd party plugins to filter template file from their plugin.
    $template = apply_filters('teg_ta_get_template_part', $template, $slug, $name);

    if ($template) {
        load_template($template, false);
    }
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function teg_ta_get_template($template_name, $args = array(), $template_path = '', $default_path = '')
{
    if (!empty($args) && is_array($args)) {
        extract($args);
    }

    $located = teg_ta_locate_template($template_name, $template_path, $default_path);

    if (!file_exists($located)) {
        teg_ta_doing_it_wrong(__FUNCTION__, sprintf(__('%s does not exist.', 'teg-twitter-api'), '<code>' . $located . '</code>'), '1.0');
        return;
    }

    // Allow 3rd party plugin filter template file from their plugin.
    $located = apply_filters('teg_ta_get_template', $located, $template_name, $args, $template_path, $default_path);

    do_action('teg_twitter_api_before_template_part', $template_name, $template_path, $located, $args);


    include($located);

    do_action('teg_twitter_api_after_template_part', $template_name, $template_path, $located, $args);
}


/**
 * Like teg_ta_get_template, but returns the HTML instead of outputting.
 *
 * @see teg_ta_get_template
 * @since 2.5.0
 * @param string $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 *
 * @return string
 */
function teg_ta_get_template_html($template_name, $args = array(), $template_path = '', $default_path = '')
{
    ob_start();
    teg_ta_get_template($template_name, $args, $template_path, $default_path);
    return ob_get_clean();
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *        yourtheme        /    $template_path    /    $template_name
 *        yourtheme        /    $template_name
 *        $default_path    /    $template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function teg_ta_locate_template($template_name, $template_path = '', $default_path = '')
{
    if (!$template_path) {
        $template_path = TEGTApi()->template_path();
    }

    if (!$default_path) {
        $default_path = TEGTApi()->plugin_path() . '/templates/';
    }

    // Look within passed path within the theme - this is priority.
    $template = locate_template(
        array(
            trailingslashit($template_path) . $template_name,
            $template_name,
        )
    );

    // Get default template/
    if (!$template || TEG_TA_TEMPLATE_DEBUG_MODE) {
        $template = $default_path . $template_name;
    }

    // Return what we found.
    return apply_filters('teg_twitter_api_locate_template', $template, $template_name, $template_path);
}

function teg_ta_include($path)
{
    $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    if (!file_exists($path)) {

        die('File not exists: ' . $path);
    }
    include_once($path);

}

function teg_ta_twitter_feed_templates()
{

    $templates = array(

        'teg-feed-tmpl1' => __('Theme Default', 'teg-twitter-api'),
        'teg-feed-tmpl2' => __('Twitter Default', 'teg-twitter-api'),
        'teg-feed-tmpl3' => __('Twitter Feeds Theme', 'teg-twitter-api'),

    );

    return apply_filters('teg_ta_twitter_feed_templates', $templates);

}

function teg_ta_twitter_trend_templates()
{

    $templates = array(

        'teg-trend-tmpl1' => __('Theme Default', 'teg-twitter-api'),
        'teg-trend-tmpl2' => __('Twitter Default', 'teg-twitter-api'),
        'teg-trend-tmpl3' => __('Twitter Trends Theme', 'teg-twitter-api'),

    );

    return apply_filters('teg_ta_twitter_trend_templates', $templates);

}


function teg_ta_twitter_feed_text_render($feed)
{
    $text = isset($feed['text']) ? esc_attr($feed['text']) : '';

    $hash_tags = isset($feed['entities']['hashtags']) ? $feed['entities']['hashtags'] : array();

    $urls = isset($feed['entities']['urls']) ? $feed['entities']['urls'] : array();


    foreach ($hash_tags as $hash_key => $hash_text) {


        if (isset($hash_text['text'])) {


            $replace_text = '<a target="_blank" href="https://twitter.com/hashtag/' . esc_attr($hash_text['text']) . '?src=hash">#' . esc_attr($hash_text['text']) . '</a>';


            $text = str_replace('#' . esc_attr($hash_text['text']), $replace_text, $text);

        }

    }
    foreach ($urls as $url_index => $url) {


        if (isset($url['url'])) {


            $replace_text = '<a target="_blank" href="' . esc_attr($url['expanded_url']) . '">' . esc_attr($url['url']) . '</a>';


            $text = str_replace(esc_attr($url['url']), $replace_text, $text);

        }

    }


    return $text;
}