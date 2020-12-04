<?php

namespace NHTSlider\Includes\Admin;

/**
 * Class register custom post type
 */
class CptSlider
{
    public function __construct()
    {
        add_action('init', [$this, 'register_custom_post_type']);
    }

    public function register_custom_post_type()
    {
        // Register CPT Slide
        $labels = array(
            'name'          => 'Slide',
            'singular_name' => 'Slide',
            'menu_name'     => 'Slide',
        );

        $args = array(
            'labels'          => $labels,
            'show_ui'         => true,
            'show_in_menu'    => true,
            'capability_type' => 'post',
            'show_ui'         => false,
            'hierarchical'    => false,
            'public'          => true,
            'supports'        => false
        );

        register_post_type('cpt-per-slide', $args);

        // Register CPT Slider
        $labels = array(
            'name'           => 'Slider',
            'singular_name'  => 'Slider',
            'menu_name'      => 'Sliders',
            'name_admin_bar' => 'Slider'
        );

        $args = array(
            'labels'          => $labels,
            'show_ui'         => true,
            'show_in_menu'    => true,
            'capability_type' => 'post',
            'hierarchical'    => false,
            'public'          => true,
            'menu_position'   => 26,
            'menu_icon'       => 'dashicons-images-alt',
            'supports'        => false
        );

        register_post_type('cpt-slider', $args);
    }
}
