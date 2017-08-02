<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG_TA_Shortcode_Interface
 *
 * Functions that must be defined shortcode classes.
 *
 * @version  1.0.0
 * @category Interface
 * @author   ThemeEgg
 */
interface TEG_TA_Shortcode_Interface
{

    public static function output($args=array());

}