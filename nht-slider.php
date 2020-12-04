<?php

/**
 * Plugin Name: NHT Admin Slider
 * Description: Plugin admin slider.
 * Author: TienNguyen
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin Constants 
define('NHTSLIDER_VERSION', '10.0.0');
define('NHTSLIDER_BASENAME', plugin_basename(__FILE__));
define('NHTSLIDER_URL', plugins_url('/', __FILE__));
define('NHTSLIDER_PATH', plugin_dir_path(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('NHTSLIDER_INCLUDES', NHTSLIDER_PATH . 'includes');

require_once NHTSLIDER_INCLUDES . '/initializer.php';
