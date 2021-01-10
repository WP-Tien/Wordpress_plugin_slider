<?php

namespace NHTSlider\Includes\Front;

/**
 * Class Enqueue script for Front
 */

class Enqueue
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'register_theme_asset']);
    }

    public function register_theme_asset()
    {
        $js_version = NHTSLIDER_BASENAME . '.' . filemtime(NHTSLIDER_PATH . 'assets/front/js/jquery.flexslider-min.js');
        wp_enqueue_script('flex-slider', NHTSLIDER_URL . 'assets/front/js/jquery.flexslider-min.js', array('jquery'), $js_version);
    }
}
