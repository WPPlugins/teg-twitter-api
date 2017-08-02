<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * TEG_TA Autoloader.
 *
 * @class        TEG_TA_Autoloader
 * @version        1.0.0
 * @package        TEG_TA/Classes
 * @category    Class
 * @author        ThemeEgg
 */
class TEG_TA_Autoloader
{

    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct()
    {
        if (function_exists("__autoload")) {
            spl_autoload_register("__autoload");
        }

        spl_autoload_register(array($this, 'autoload'));

        $this->include_path = untrailingslashit(plugin_dir_path(TEG_TA_PLUGIN_FILE)) . '/includes/';
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param  string $class
     * @return string
     */
    private function get_file_name_from_class($class)
    {
        return 'class-' . str_replace('_', '-', $class) . '.php';
    }

    /**
     * Include a class file.
     *
     * @param  string $path
     * @return bool successful or not
     */
    private function load_file($path)
    {
        if ($path && is_readable($path)) {
            include_once($path);
            return true;
        }
        return false;
    }

    /**
     * Auto-load TEG_TA classes on demand to reduce memory consumption.
     *
     * @param string $class
     */

    //TEG_TA_Api_Twitter_Trends
    public function autoload($class)
    {
        $class = strtolower($class);


        if (0 !== strpos($class, 'teg_ta_')) {
            return;
        }


        $file = $this->get_file_name_from_class($class);
        $path = '';

        if (strpos($class, 'teg_ta_shortcode_') === 0) {
            $path = $this->include_path . 'shortcodes/';
        } elseif (strpos($class, 'teg_ta_meta_box') === 0) {
            $path = $this->include_path . 'admin/meta-boxes/';
        } elseif (strpos($class, 'teg_ta_admin') === 0) {
            $path = $this->include_path . 'admin/';
        } elseif (strpos($class, 'teg_ta_api_') === 0) {

            $path = $this->include_path . 'api/twitter/';

        }


        if (empty($path) || !$this->load_file($path . $file)) {
            $this->load_file($this->include_path . $file);
        }
    }
}

new TEG_TA_Autoloader();
