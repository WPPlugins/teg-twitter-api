<?php
/**
 * TEGTwitterAPI General Settings
 *
 * @author      ThemeEgg
 * @category    Admin
 * @package     TEGTwitterAPI/Admin
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('TEG_TA_Settings_General', false)) :

    /**
     * TEG_TA_Admin_Settings_General.
     */
    class TEG_TA_Settings_General extends TEG_TA_Settings_Page
    {

        /**
         * Constructor.
         */
        public function __construct()
        {

            $this->id = 'general';
            $this->label = __('General', 'teg-twitter-api');

            add_filter('teg_twitter_api_settings_tabs_array', array($this, 'add_settings_page'), 20);
            add_action('teg_twitter_api_settings_' . $this->id, array($this, 'output'));
            add_action('teg_twitter_api_settings_save_' . $this->id, array($this, 'save'));
        }

        /**
         * Get settings array.
         *
         * @return array
         */
        public function get_settings()
        {


            $settings =array(

                array('title' => __('General options', 'teg-twitter-api'), 'type' => 'title', 'desc' => '', 'id' => 'general_options'),

                array(
                    'title' => __('Post on twitter', 'teg-twitter-api'),
                    'desc' => __('Post on twitter', 'teg-twitter-api'),
                    'id' => 'teg_twitter_api_post_on_twitter',
                    'default' => 'yes',
                    'type' => 'checkbox',
                    'checkboxgroup' => 'start',
                    'show_if_checked' => 'option',
                ),


                array('type' => 'sectionend', 'id' => 'general_options'),

            );


            return apply_filters('teg_twitter_api_get_settings_' . $this->id, $settings);

        }

        /**
         * Output a color picker input box.
         *
         * @param mixed $name
         * @param string $id
         * @param mixed $value
         * @param string $desc (default: '')
         */
        public function color_picker($name, $id, $value, $desc = '')
        {
            echo '<div class="color_box">' . teg_ta_help_tip($desc) . '
			<input name="' . esc_attr($id) . '" id="' . esc_attr($id) . '" type="text" value="' . esc_attr($value) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr($id) . '" class="colorpickdiv"></div>
		</div>';
        }

        /**
         * Save settings.
         */
        public function save()
        {

           // $settings = $this->get_settings();


            //TEG_TA_Admin_Settings::save_fields($settings);
        }
    }

endif;

return new TEG_TA_Settings_General();
