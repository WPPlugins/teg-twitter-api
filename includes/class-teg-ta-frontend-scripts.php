<?php
/**
 * Handle frontend scripts
 *
 * @class       TEG_TA_Frontend_Scripts
 * @version     1.0.0
 * @package     TEG_Twitte_Api/Classes/
 * @category    Class
 * @author      ThemeEgg
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG_TA_Frontend_Scripts Class.
 */
class TEG_TA_Frontend_Scripts
{

    /**
     * Contains an array of script handles registered by TEG_TA.
     * @var array
     */
    private static $scripts = array();

    /**
     * Contains an array of script handles registered by TEG_TA.
     * @var array
     */
    private static $styles = array();

    /**
     * Contains an array of script handles localized by TEG_TA.
     * @var array
     */
    private static $wp_localize_scripts = array();

    /**
     * Hook in methods.
     */
    public static function init()
    {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'load_scripts'));
        add_action('wp_print_scripts', array(__CLASS__, 'localize_printed_scripts'), 5);
        add_action('wp_print_footer_scripts', array(__CLASS__, 'localize_printed_scripts'), 5);

    }


    /**
     * Get styles for the frontend.
     *
     * @return array
     */
    public static function get_styles()
    {
        return apply_filters('teg_twitter_api_enqueue_styles', array(
            'teg-twitter-api-frontend-style' => array(
                'src' => self::get_asset_url('assets/css/frontend-teg-twitter-api.css'),
                'deps' => '',
                'version' => TEG_TA_VERSION,
                'media' => 'all',
                'has_rtl' => true,
            ),

        ));
    }

    /**
     * Return protocol relative asset URL.
     *
     * @param string $path
     *
     * @return string
     */
    private static function get_asset_url($path)
    {
        return str_replace(array('http:', 'https:'), '', plugins_url($path, TEG_TA_PLUGIN_FILE));
    }

    /**
     * Register a script for use.
     *
     * @uses   wp_register_script()
     * @access private
     * @param  string $handle
     * @param  string $path
     * @param  string[] $deps
     * @param  string $version
     * @param  boolean $in_footer
     */
    private static function register_script($handle, $path, $deps = array('jquery'), $version = TEG_TA_VERSION, $in_footer = true)
    {
        self::$scripts[] = $handle;
        wp_register_script($handle, $path, $deps, $version, $in_footer);
    }

    /**
     * Register and enqueue a script for use.
     *
     * @uses   wp_enqueue_script()
     * @access private
     * @param  string $handle
     * @param  string $path
     * @param  string[] $deps
     * @param  string $version
     * @param  boolean $in_footer
     */
    private static function enqueue_script($handle, $path = '', $deps = array('jquery'), $version = TEG_TA_VERSION, $in_footer = true)
    {
        if (!in_array($handle, self::$scripts) && $path) {
            self::register_script($handle, $path, $deps, $version, $in_footer);
        }
        wp_enqueue_script($handle);
    }

    /**
     * Register a style for use.
     *
     * @uses   wp_register_style()
     * @access private
     * @param  string $handle
     * @param  string $path
     * @param  string[] $deps
     * @param  string $version
     * @param  string $media
     * @param  boolean $has_rtl
     */
    private static function register_style($handle, $path, $deps = array(), $version = TEG_TA_VERSION, $media = 'all', $has_rtl = false)
    {
        self::$styles[] = $handle;
        wp_register_style($handle, $path, $deps, $version, $media);

        if ($has_rtl) {
            wp_style_add_data($handle, 'rtl', 'replace');
        }
    }

    /**
     * Register and enqueue a styles for use.
     *
     * @uses   wp_enqueue_style()
     * @access private
     * @param  string $handle
     * @param  string $path
     * @param  string[] $deps
     * @param  string $version
     * @param  string $media
     * @param  boolean $has_rtl
     */
    private static function enqueue_style($handle, $path = '', $deps = array(), $version = TEG_TA_VERSION, $media = 'all', $has_rtl = false)
    {
        if (!in_array($handle, self::$styles) && $path) {
            self::register_style($handle, $path, $deps, $version, $media, $has_rtl);
        }
        wp_enqueue_style($handle);
    }

    /**
     * Register all TEGTApi() scripts.
     */
    private static function register_scripts()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        $register_scripts = array(
            'teg-twitter-api-frontend-script' => array(
                'src' => self::get_asset_url('assets/js/frontend/teg-twitter-api-frontend' . $suffix . '.js'),
                'deps' => array('jquery'),
                'version' => TEG_TA_VERSION,
            )

        );
        foreach ($register_scripts as $name => $props) {
            self::register_script($name, $props['src'], $props['deps'], $props['version']);
        }
    }

    /**
     * Register all TEGTApi() sty;es.
     */
    private static function register_styles()
    {
        $register_styles = array(
            'teg-twitter-api-frontend-style' => array(
                'src' => self::get_asset_url('assets/css/frontend-teg-twitter-api.css'),
                'deps' => array(),
                'version' => TEG_TA_VERSION,
                'has_rtl' => false,
            ),

        );
        foreach ($register_styles as $name => $props) {
            self::register_style($name, $props['src'], $props['deps'], $props['version'], 'all', $props['has_rtl']);
        }
    }

    /**
     * Register/queue frontend scripts.
     */
    public static function load_scripts()
    {
        global $post;

        if (!did_action('before_teg_twitter_api_init')) {
            return;
        }

        self::register_scripts();
        self::register_styles();

        // Global frontend scripts
        self::enqueue_script('teg-twitter-api-frontend-script');

        // CSS Styles
        if ($enqueue_styles = self::get_styles()) {
            foreach ($enqueue_styles as $handle => $args) {
                if (!isset($args['has_rtl'])) {
                    $args['has_rtl'] = false;
                }

                self::enqueue_style($handle, $args['src'], $args['deps'], $args['version'], $args['media'], $args['has_rtl']);
            }
        }
    }

    /**
     * Localize a TEGTApi() script once.
     * @access private
     * @since  2.3.0 this needs less wp_script_is() calls due to https://core.trac.wordpress.org/ticket/28404 being added in WP 4.0.
     * @param  string $handle
     */
    private static function localize_script($handle)
    {
        if (!in_array($handle, self::$wp_localize_scripts) && wp_script_is($handle) && ($data = self::get_script_data($handle))) {
            $name = str_replace('-', '_', $handle) . '_params';
            self::$wp_localize_scripts[] = $handle;
            wp_localize_script($handle, $name, apply_filters($name, $data));
        }
    }

    /**
     * Return data for script handles.
     * @access private
     * @param  string $handle
     * @return array|bool
     */
    private static function get_script_data($handle)
    {
        global $wp;

        switch ($handle) {
            case 'teg-twitter-api-frontend-script' :
                return array(
                    'ajax_url' => TEGTApi()->ajax_url(),
                );
                break;
        }
        return false;
    }

    /**
     * Localize scripts only when enqueued.
     */
    public static function localize_printed_scripts()
    {
        foreach (self::$scripts as $handle) {
            self::localize_script($handle);
        }
    }
}

TEG_TA_Frontend_Scripts::init();
