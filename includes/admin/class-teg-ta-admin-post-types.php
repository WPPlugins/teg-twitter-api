<?php
/**
 * Post Types Admin
 *
 * @author   ThemeEgg
 * @category Admin
 * @package  TEGTwitterApi/Admin
 * @version  1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('TEG_TA_Admin_Post_Types', false)) :

    /**
     * TEG_TA_Admin_Post_Types Class.
     *
     * Handles the edit posts views and some functionality on the edit post screen for TEGTApi() post types.
     */
    class TEG_TA_Admin_Post_Types
    {

        /**
         * Constructor.
         */
        public function __construct()
        {

            include_once(dirname(__FILE__) . '/class-teg-ta-admin-meta-boxes.php');
            // Disable DFW feature pointer

        }


    }

endif;

new TEG_TA_Admin_Post_Types();
