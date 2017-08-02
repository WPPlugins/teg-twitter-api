<?php
/**
 * Installation related functions and actions.
 *
 * @author   ThemeEgg
 * @category Admin
 * @package  TEG_TA_Twitter_Api/Classes
 * @version  1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG_TA_Install Class.
 */
class TEG_TA_Install
{

    /** @var object Background update class */
    private static $background_updater;

    /** @var array DB updates and callbacks that need to be run per version */
    private static $db_updates = array();

    /**
     * Hook in tabs.
     */
    public static function init()
    {
        add_action('init', array(__CLASS__, 'check_version'), 5);
        add_action('admin_init', array(__CLASS__, 'install_actions'));

    }

    /**
     * Check TEG_TA_Twitter_Api version and run the updater is required.
     *
     * This check is done on all requests and runs if the versions do not match.
     */
    public static function check_version()
    {
        if (!defined('IFRAME_REQUEST') && get_option('teg_ta_version') !== TEGTApi()->version) {
            self::install();
            do_action('teg_twitter_api_updated');
        }
    }

    /**
     * Install actions when a update button is clicked within the admin area.
     *
     * This function is hooked into admin_init to affect admin only.
     */
    public static function install_actions()
    {


    }

    /**
     * Install TEG_Twitter_Api.
     */
    public static function install()
    {
        global $wpdb;

        if (!is_blog_installed()) {
            return;
        }

        if (!defined('TEG_TA_INSTALLING')) {
            define('TEG_TA_INSTALLING', true);
        }

        self::create_options();
       
        // Trigger action
        do_action('teg_twitter_api_installed');
    }


    /**
     * Default options.
     *
     * Sets up the default options used on the settings page.
     */
    private static function create_options()
    {
        // Include settings so that we can run through defaults
        include_once(dirname(__FILE__) . '/admin/class-teg-ta-admin-settings.php');

        $settings = TEG_TA_Admin_Settings::get_settings_pages();

        foreach ($settings as $section) {
            if (!method_exists($section, 'get_settings')) {
                continue;
            }
            $subsections = array_unique(array_merge(array(''), array_keys($section->get_sections())));

            foreach ($subsections as $subsection) {
                foreach ($section->get_settings($subsection) as $value) {
                    if (isset($value['default']) && isset($value['id'])) {
                        $autoload = isset($value['autoload']) ? (bool)$value['autoload'] : true;
                        add_option($value['id'], $value['default'], '', ($autoload ? 'yes' : 'no'));
                    }
                }
            }
        }
    }

}

TEG_TA_Install::init();
