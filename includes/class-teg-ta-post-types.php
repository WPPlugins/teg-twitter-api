<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @class     TEG_TA_Post_types
 * @version   1.0.0
 * @package   TEGTwitterAPI/Classes/Products
 * @category  Class
 * @author    ThemeEgg
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * TEG_TA_Post_types Class.
 */
class TEG_TA_Post_types {

    /**
     * Hook in methods.
     */
    public static function init() {
//        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
//        add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
//        add_action( 'init', array( __CLASS__, 'register_post_status' ), 9 );

    }
}

TEG_TA_Post_types::init();
