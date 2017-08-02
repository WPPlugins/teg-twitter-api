<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG_TA_Option_Interface
 *
 * Functions that must be defined options classes.
 *
 * @version  1.0.0
 * @category Interface
 * @author   ThemeEgg
 */
interface TEG_TA_Option_Interface
{
    function __construct();

    public function addActionHook();

    public function addOptionsPages();

    public function registerThemeOptionGroup();

    public function templates();

    function __destruct();
}