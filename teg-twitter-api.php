<?php
/**
 * Plugin Name: Twitter API Master
 * Plugin URI: https://themeegg.com/plugins/teg-twitter-api
 * Description: This plugin for Twitter Widgets Shortcodes and Many more.
 * Version: 1.1.1
 * Author: ThemeEgg
 * Author URI: https://themeegg.com
 * Requires at least: 3.0.1
 * Tested up to: 4.8
 *
 * Text Domain: teg-twitter-api
 * Domain Path: /i18n/languages/
 *
 * @package TEGTwitterApi
 * @category Core
 * @author ThemeEgg
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('TEG_Twitter_Api')) :

    /**
     * Main TEG_Twitter_Api Class.
     *
     * @class TEG_Twitter_Api
     * @version    3.1.0
     */
    final class TEG_Twitter_Api
    {

        /**
         * TEG_Twitter_Api version.
         *
         * @var string
         */
        public $version = '1.0.0';

        /**
         * Query instance.
         *
         *
         */
        public $query = null;

        /**
         * The single instance of the class.
         *
         * @var TEG_Twitter_Api
         * @since 1.0
         */
        protected static $_instance = null;


        /**
         * Main TEG_Twitter_Api Instance.
         *
         * Ensures only one instance of TEG_Twitter_Api is loaded or can be loaded.
         *
         * @since 1.0
         * @static
         * @see TEGTApi()
         * @return TEG_Twitter_Api - Main instance.
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Cloning is forbidden.
         * @since 1.0
         */
        public function __clone()
        {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'teg-twitter-api'), '1.0');
        }

        /**
         * Unserializing instances of this class is forbidden.
         * @since 1.0
         */
        public function __wakeup()
        {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'teg-twitter-api'), '1.0');
        }

        /**
         * Auto-load in-accessible properties on demand.
         * @param mixed $key
         * @return mixed
         */
        public function __get($key)
        {

            return $this->$key();

        }

        /**
         * TEG_Twitter_Api Constructor.
         */
        public function __construct()
        {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();

            do_action('teg_twitter_api_loaded');
        }

        /**
         * Hook into actions and filters.
         * @since  1.0
         */
        private function init_hooks()
        {
            register_activation_hook(__FILE__, array('TEG_TA_Install', 'install'));
            add_action('after_setup_theme', array($this, 'setup_environment'));
            add_action('after_setup_theme', array($this, 'include_template_functions'), 11);
            add_action('init', array($this, 'init'), 0);
            add_action('init', array('TEG_TA_Shortcodes', 'init'));


        }

        /**
         * Define TEG_TA Constants.
         */
        private function define_constants()
        {
            $upload_dir = wp_upload_dir();

            $this->define('TEG_TA_DS', DIRECTORY_SEPARATOR);
            $this->define('TEG_TA_PLUGIN_FILE', __FILE__);
            $this->define('TEG_TA_ABSPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
            $this->define('TEG_TA_PLUGIN_BASENAME', plugin_basename(__FILE__));
            $this->define('TEG_TA_VERSION', $this->version);
            $this->define('TEG_TA_LOG_DIR', $upload_dir['basedir'] . '/teg-logs/');
            $this->define('TEG_TA_TEMPLATE_DEBUG_MODE', false);
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define($name, $value)
        {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        /**
         * What type of request is this?
         *
         * @param  string $type admin, ajax, cron or frontend.
         * @return bool
         */
        private function is_request($type)
        {
            switch ($type) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined('DOING_AJAX');
                case 'cron' :
                    return defined('DOING_CRON');
                case 'frontend' :
                    return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
            }
        }

        /**
         * Check the active theme.
         *
         * @since  2.6.9
         * @param  string $theme Theme slug to check
         * @return bool
         */
        private function is_active_theme($theme)
        {
            return get_template() === $theme;
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        public function includes()
        {
            /**
             * Class autoloader.
             */

            include(TEG_TA_ABSPATH . 'includes' . TEG_TA_DS . 'teg-ta-core-functions.php');


            teg_ta_include(TEG_TA_ABSPATH . 'includes/class-teg-ta-autoloader.php');

            /**
             * Interfaces.
             */
            teg_ta_include(TEG_TA_ABSPATH . 'includes/interfaces/class-teg-ta-meta-box-interface.php');
            teg_ta_include(TEG_TA_ABSPATH . 'includes/interfaces/class-teg-ta-option-interface.php');
            teg_ta_include(TEG_TA_ABSPATH . 'includes/interfaces/class-teg-ta-shortcode-interface.php');
            teg_ta_include(TEG_TA_ABSPATH . 'includes/interfaces/class-teg-ta-widget-interface.php');

            /**
             * Core classes.
             */


            teg_ta_include(TEG_TA_ABSPATH . 'includes/class-teg-ta-post-types.php'); // Registers post types
            teg_ta_include(TEG_TA_ABSPATH . 'includes/class-teg-ta-install.php');
            teg_ta_include(TEG_TA_ABSPATH . 'includes/class-teg-ta-ajax.php');


            if ($this->is_request('admin')) {
                teg_ta_include(TEG_TA_ABSPATH . 'includes/admin/class-teg-ta-admin.php');
            }

            if ($this->is_request('frontend')) {
                $this->frontend_includes();
            }
            $this->query = new TEG_TA_Query();


        }

        /**
         * Include required frontend files.
         */
        public function frontend_includes()
        {


            teg_ta_include(TEG_TA_ABSPATH . 'includes/class-teg-ta-frontend-scripts.php');               // Frontend Scripts


            teg_ta_include(TEG_TA_ABSPATH . 'includes/class-teg-ta-shortcodes.php');                     // Shortcodes class


        }

        /**
         * Function used to Init TEG_Twitter_Api Template Functions - This makes them pluggable by plugins and themes.
         */
        public function include_template_functions()
        {
        }

        /**
         * Init TEG_Twitter_Api when WordPress Initialises.
         */
        public function init()
        {
            // Before init action.
            do_action('before_teg_twitter_api_init');

            // Set up localisation.
            $this->load_plugin_textdomain();

            $this->load_webhooks();

            // Init action.
            do_action('teg_twitter_api_init');
        }

        /**
         * Load Localisation files.
         *
         * Note: the first-loaded translation file overrides any following ones if the same translation is present.
         *
         * Locales found in:
         *      - WP_LANG_DIR/teg-twitter-api/teg-twitter-api-LOCALE.mo
         *      - WP_LANG_DIR/plugins/teg-twitter-api-LOCALE.mo
         */
        public function load_plugin_textdomain()
        {
            $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
            $locale = apply_filters('plugin_locale', $locale, 'teg-twitter-api');

            unload_textdomain('teg-twitter-api');
            load_textdomain('teg-twitter-api', WP_LANG_DIR . '/teg-twitter-api/teg-twitter-api-' . $locale . '.mo');
            load_plugin_textdomain('teg-twitter-api', false, plugin_basename(dirname(__FILE__)) . '/i18n/languages');
        }

        /**
         * Ensure theme and server variable compatibility and setup image sizes.
         */
        public function setup_environment()
        {
            /**
             * @deprecated 2.2 Use TEGTApi()->template_path()
             */
            $this->define('TEG_TA_TEMPLATE_PATH', $this->template_path());


        }


        /**
         * Get the plugin url.
         * @return string
         */
        public function plugin_url()
        {
            return untrailingslashit(plugins_url('/', __FILE__));
        }

        /**
         * Get the plugin path.
         * @return string
         */
        public function plugin_path()
        {
            return untrailingslashit(plugin_dir_path(__FILE__));
        }

        /**
         * Get the template path.
         * @return string
         */
        public function template_path()
        {
            return apply_filters('teg_twitter_api_template_path', 'teg-twitter-api/');
        }

        /**
         * Get Ajax URL.
         * @return string
         */
        public function ajax_url()
        {
            return admin_url('admin-ajax.php', 'relative');
        }

        /**
         * Return the TEGTApi() API URL for a given request.
         *
         * @param string $request
         * @param mixed $ssl (default: null)
         * @return string
         */
        public function api_request_url($request, $ssl = null)
        {
            if (is_null($ssl)) {
                $scheme = parse_url(home_url(), PHP_URL_SCHEME);
            } elseif ($ssl) {
                $scheme = 'https';
            } else {
                $scheme = 'http';
            }

            if (strstr(get_option('permalink_structure'), '/index.php/')) {
                $api_request_url = trailingslashit(home_url('/index.php/wc-api/' . $request, $scheme));
            } elseif (get_option('permalink_structure')) {
                $api_request_url = trailingslashit(home_url('/wc-api/' . $request, $scheme));
            } else {
                $api_request_url = add_query_arg('wc-api', $request, trailingslashit(home_url('', $scheme)));
            }

            return esc_url_raw(apply_filters('teg_twitter_api_api_request_url', $api_request_url, $request, $ssl));
        }

        /**
         * Load & enqueue active webhooks.
         *
         * @since 2.2
         */
        private function load_webhooks()
        {

            if (!is_blog_installed()) {
                return;
            }

            if (false === ($webhooks = get_transient('teg_twitter_api_webhook_ids'))) {
                $webhooks = get_posts(array(
                    'fields' => 'ids',
                    'post_type' => 'shop_webhook',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                ));
                set_transient('teg_twitter_api_webhook_ids', $webhooks);
            }
            foreach ($webhooks as $webhook_id) {
                $webhook = new TEG_TA_Webhook($webhook_id);
                $webhook->enqueue();
            }
        }

        /**
         * TEG_Twitter_Api Payment Token Meta API and Term/Order item Meta - set table names.
         */
        public function wpdb_table_fix()
        {
            global $wpdb;
            $wpdb->payment_tokenmeta = $wpdb->prefix . 'teg_twitter_api_payment_tokenmeta';
            $wpdb->order_itemmeta = $wpdb->prefix . 'teg_twitter_api_order_itemmeta';
            $wpdb->tables[] = 'teg_twitter_api_payment_tokenmeta';
            $wpdb->tables[] = 'teg_twitter_api_order_itemmeta';

            if (get_option('db_version') < 34370) {
                $wpdb->teg_twitter_api_termmeta = $wpdb->prefix . 'teg_twitter_api_termmeta';
                $wpdb->tables[] = 'teg_twitter_api_termmeta';
            }
        }

    }

endif;

/**
 * Main instance of TEG_Twitter_Api.
 *
 * Returns the main instance of TEGTApi() to prevent the need to use globals.
 *
 * @since  1.0
 * @return TEG_Twitter_Api
 */
function TEGTApi()
{
    return TEG_Twitter_Api::instance();
}

// Global for backwards compatibility.
$GLOBALS['teg_twitter_api'] = TEGTApi();
