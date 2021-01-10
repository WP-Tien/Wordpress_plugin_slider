<?php

namespace NHTSlider\Includes;

class Admin
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'register_scripts']);
    }

    public function register_scripts($hook)
    {
        // silder admin [cpt]
        if (isset($_GET['post'])) {
            $post = get_post($_GET['post']);

            if ($post->post_type == 'cpt-slider' && $hook == 'post.php') {
                $css_version = NHTSLIDER_BASENAME . '.' . filemtime(NHTSLIDER_PATH . 'assets/admin/css/main.css');
                wp_register_style('slider-style', NHTSLIDER_URL . 'assets/admin/css/main.css', array(), $css_version, 'all');
                wp_enqueue_style('slider-style');

                wp_enqueue_media();

                $js_version = NHTSLIDER_BASENAME . '.' . filemtime(NHTSLIDER_PATH . 'assets/admin/js/main.js');
                wp_register_script('slider-script', NHTSLIDER_URL . 'assets/admin/js/main.js', array('jquery'), $js_version, true);
                wp_enqueue_script('slider-script');

                wp_localize_script(
                    'slider-script',
                    'slider_object',
                    [
                        'ajax_url' => admin_url('admin-ajax.php'),
                        'no_img' => NHTSLIDER_URL . 'assets/admin/images/no-image.png'
                    ]
                );
            }
        }
    }
}
