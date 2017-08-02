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

if (!class_exists('TET_TA_Settings_Layouts', false)) :

    /**
     * TET_TA_Settings_Layouts.
     */
    class TET_TA_Settings_Layouts extends TEG_TA_Settings_Page
    {

        /**
         * Constructor.
         */
        public function __construct()
        {

            $this->id = 'layouts';
            $this->label = __('Layouts', 'teg-twitter-api');

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
                '' => __('Twitter Feeds', 'teg-twitter-api'),

                'trends' => __('Twitter Trends', 'teg-twitter-api'),

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
            if ('trends' == $current_section) {

                $settings = apply_filters('teg_twitter_layout_settings', array(

                    array(
                        'title' => __('Trends Layout', 'teg-twitter-api'),
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'teg_ta_twitter_trend_shortcode_layout_setting_options',
                    ),
                    array(
                        'title' => __('Templates', 'teg-twitter-api'),
                        'desc' => __('Layout tempaltes .', 'teg-twitter-api'),
                        'id' => 'teg_ta_twitter_trend_shortcode_layout',
                        'default' => 'teg-trend-tmpl1',
                        'type' => 'select',
                        'class' => 'teg-select',
                        'css' => 'min-width: 350px;',
                        'desc_tip' => true,
                        'autoload' => false,
                        'options' => teg_ta_twitter_trend_templates(),
                    ), array(
                        'type' => 'sectionend',
                        'id' => 'teg_twitter_layout_settings',
                    ),


                ));

            } else {
                $settings = apply_filters('teg_twitter_api_general_settings', array(
                    array(
                        'title' => __('Twitter Timeline Layout', 'teg-twitter-api'),
                        'type' => 'title',
                        'id' => 'teg_ta_twitter_feed_shortcode_layout_setting_options',
                    ),

                    array(
                        'title' => __('Templates ', 'teg-twitter-api'),
                        'desc' => __('Twiter feed layout s', 'teg-twitter-api'),
                        'id' => 'teg_ta_twitter_feed_shortcode_layout',
                        'default' => 'teg-feed-tmpl1',
                        'type' => 'select',
                        'class' => 'teg-select',
                        'css' => 'min-width: 350px;',
                        'desc_tip' => true,
                        'options' => teg_ta_twitter_feed_templates(),
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

return new TET_TA_Settings_Layouts();
