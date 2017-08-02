<?php
/**
 * TEG Twitter API Product Settings
 *
 * @author   ThemeEgg
 * @category Admin
 * @package  TEG_Twitter_Api/Admin
 * @version  1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('TET_TA_Settings_API', false)) :

    /**
     * TET_TA_Settings_API.
     */
    class TET_TA_Settings_API extends TEG_TA_Settings_Page
    {

        /**
         * Constructor.
         */
        public function __construct()
        {

            $this->id = 'api';
            $this->label = __('API', 'teg-twitter-api');

            add_filter('teg_twitter_api_settings_tabs_array', array($this, 'add_settings_page'), 20);
            add_action('teg_twitter_api_settings_' . $this->id, array($this, 'output'));
            add_action('teg_twitter_api_settings_save_' . $this->id, array($this, 'save'));
            add_action('teg_twitter_api_sections_' . $this->id, array($this, 'output_sections'));
        }

        /**
         * Get sections.
         *
         * @return array
         */
        public function get_sections()
        {

            $sections = array(
                '' => __('Basic', 'teg-twitter-api'),

                'other' => __('Other', 'teg-twitter-api'),

            );

            return apply_filters('teg_twitter_api_get_sections_' . $this->id, $sections);
        }

        /**
         * Output the settings.
         */
        public function output()
        {
            global $current_section;

            $settings = $this->get_settings($current_section);

            TEG_TA_Admin_Settings::output_fields($settings);
        }

        /**
         * Save settings.
         */
        public function save()
        {
            global $current_section;

            $settings = $this->get_settings($current_section);
            TEG_TA_Admin_Settings::save_fields($settings);
        }

        /**
         * Get settings array.
         *
         * @param string $current_section
         *
         * @return array
         */
        public function get_settings($current_section = '')
        {
            if ('other' == $current_section) {

                $settings = apply_filters('teg_twitter_api_key_settings', array(

                    array(
                        'title' => __('Key Settings', 'teg-twitter-api'),
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'teg_twitter_api_key_options',
                    ),
                    array(
                        'title' => __('Twitter Oauth Access Token	', 'teg-twitter-api'),
                        'desc' => __('Please enter Oauth access Token.', 'teg-twitter-api'),
                        'id' => 'teg_twitter_api_twitter_oauth_access_token',
                        'type' => 'text',
                        'default' => '',
                        'css' => 'width: 250px;',
                        'autoload' => false,
                        'desc_tip' => true,
                        'class' => '',
                    ), array(
                        'title' => __('Twitter Oauth Access Token Secret.', 'teg-twitter-api'),
                        'desc' => __('Please Oauth Access Token Secret.', 'teg-twitter-api'),
                        'id' => 'teg_twitter_api_twitter_oauth_token_secret',
                        'type' => 'text',
                        'default' => '',
                        'css' => 'width: 250px;',
                        'autoload' => false,
                        'desc_tip' => true,
                        'class' => '',
                    ), array(
                        'title' => __('Twitter Consumer Key', 'teg-twitter-api'),
                        'desc' => __('Please enter twitter consumer key.', 'teg-twitter-api'),
                        'id' => 'teg_twitter_api_twitter_consumer_key',
                        'type' => 'text',
                        'default' => '',
                        'css' => 'width: 250px;',
                        'autoload' => false,
                        'desc_tip' => true,
                        'class' => '',
                    ), array(
                        'title' => __('Twitter Consumer Secret', 'teg-twitter-api'),
                        'desc' => __('Please enter Twitter Consumer Secret.', 'teg-twitter-api'),
                        'id' => 'teg_twitter_api_twitter_consumer_secret',
                        'type' => 'text',
                        'default' => '',
                        'css' => 'width: 250px;',
                        'autoload' => false,
                        'desc_tip' => true,
                        'class' => '',
                    ),
                    array(
                        'title' => '',
                        'type' => 'descriptions',
                        'label' => false,
                        'desc' => 'For Tweet shortcode please type [twitter_feeds count="4"]<br/>For Trends shortcode please type [twitter_trends count="6"]',
                        'id' => 'teg_twitter_twitter_tweets_count_info',

                    ),
                    array(
                        'type' => 'sectionend',
                        'id' => 'teg_twitter_api_key_settings',
                    ),


                ));

            } else {
                $settings = apply_filters('teg_twitter_api_general_settings', array(
                    array(
                        'title' => __('Basic Settings', 'teg-twitter-api'),
                        'type' => 'title',
                        'id' => 'teg_twitter_basic_settings_options',
                    ),


                    array(
                        'title' => __('Twitter Username', 'teg-twitter-api'),
                        'desc' => __('Twitter username', 'teg-twitter-api'),
                        'id' => 'teg_twitter_api_twitter_username',
                        'type' => 'text',
                        'default' => 'theme_egg',
                        'css' => 'width: 250px;',
                        'autoload' => false,
                        'desc_tip' => true,
                        'class' => '',
                    ),

                    array(
                        'type' => 'sectionend',
                        'id' => 'product_rating_options',
                    ),

                ));
            }

            return apply_filters('teg_twitter_api_get_settings_' . $this->id, $settings, $current_section);
        }
    }

endif;

return new TET_TA_Settings_API();
