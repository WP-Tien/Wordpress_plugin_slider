<?php

namespace NHTSlider;

use NHTSlider\Includes\Front as Front;
use NHTSlider\Includes\Admin as Admin;
use NHTSlider\Includes\Admin\CptSlider as CptSlider;
use NHTSlider\Includes\Admin\CptCss as CptCss;
use NHTSlider\Includes\Admin\CptMetaBox as CptMetaBox;
use NHTSlider\Includes\Admin\CptCol as CptCol;

defined('ABSPATH') or die('Something wrong with you !');

final class NHTSlider
{
    private static $instance = null;

    /**
     * Instance
     * 
     * Ensures only one instance of the plugin class is loaded or can be loaded.
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Clone.
     * 
     * Disable class cloning and throw an error on object clone.
     * 
     * The whole idea of the singleton design pattern is that there is a singleton object.
     * Therefore, we don't want the object to be cloned.
     */
    public function __clone()
    {
        // Cloning instance of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, esc_html__('Something went wrong.', 'tiennguyen'), '1.0.0');
    }

    /**
     * Activate the plugin
     */
    public function activate()
    {
    }

    /**
     * Dectivate the plugin
     */
    public function deactivate()
    {
    }

    public function initializer()
    {
        // Activation
        register_activation_hook(NHTSLIDER_BASENAME, [$this, 'activate']);

        // Deactivation 
        register_deactivation_hook(NHTSLIDER_BASENAME, [$this, 'deactivate']);
    }

    /**
     * Register autoload 
     * 
     * Autoload accessory files in the includes folder.
     * To use it you must construct the instances like below.
     */
    private static function register_autoload()
    {
        require_once NHTSLIDER_INCLUDES . '/autoload.php';
        Autoloader::autoload();

        // Construct the instances.
        if (is_admin()) {
            new Admin();
            // Custom post type
            new CptSlider();
            new CptCss();
            new CptMetaBox();
            new CptCol();
        } else {
            new Front();
        }
    }

    public function __construct()
    {
        NHTSlider::register_autoload();
    }
}

NHTSlider::instance();
