<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG_TA_Meta_Box Interface
 *
 * Functions that must be defined meta box classes.
 *
 * @version  1.0.0
 * @category Interface
 * @author   ThemeEgg
 */
interface TEG_TA_Meta_Box_Interface
{

    /**
     * Constructor.
     */
    public function __construct();

    /**
     * Meta box initialization.
     */
    public function init_metabox();

    /**
     * Adds the meta box.
     */
    public function add_metabox();

    /**
     * Renders the meta box.
     */
    public function render_metabox($post);

    /**
     * Handles saving the meta box.
     *
     * @param int $post_id Post ID.
     * @param WP_Post $post Post object.
     * @return null
     */
    public function save_metabox($post_id, $post);


    /**
     * Handles notices the meta box.
     * @return null
     */
    public function admin_notices();

    function __destruct();

}