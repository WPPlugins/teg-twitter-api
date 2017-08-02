<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG_TA_Widget_Interface
 *
 * Functions that must be defined widgets classes.
 *
 * @version  1.0.0
 * @category Interface
 * @author   ThemeEgg
 */
interface TEG_TA_Widget_Interface
{
    function __construct();

    public function widget($args, $instance);

    public function update($new_instance, $old_instance);

    public function form($instance);

    function __destruct();
}