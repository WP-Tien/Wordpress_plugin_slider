<?php

namespace NHTSlider\Includes\Admin;

/**
 * Add custom meta box for slider
 */
class CptMetaBox
{
    public function __construct()
    {
        // This hook to handle on load-post-new
        add_action('load-post-new.php', [$this, 'init_metabox_load_post_new']);
        // This hook to handle on load-post
        add_action('load-post.php', [$this, 'init_metabox_load_post']);
    }

    // ------------------------------------------------------------------
    // ------------------------------------------------------------------
    // ------------------------------------------------------------------
    public function init_metabox_load_post_new()
    {
        add_action('add_meta_boxes', [$this, 'cpt_add_slider_meta_box']);
    }

    public function init_metabox_load_post()
    {
        add_action('add_meta_boxes', [$this, 'cpt_add_slide_meta_box']);
    }
    // ------------------------------------------------------------------
    // ------------------------------------------------------------------
    // ------------------------------------------------------------------

    public function cpt_add_slider_meta_box()
    {
        add_meta_box('slide_info', 'Main Slider Setting', [$this, 'cpt_setting_callback'], 'cpt-slider', 'advanced', 'high');
    }

    function cpt_setting_callback($post)
    {
        wp_nonce_field('cpt_slider_data', 'cpt_slider_meta_box_nonce');

        $value_title = get_post_meta($post->ID, '_title_slider_value_key', true);
        $value_description = get_post_meta($post->ID, '_description_slider_value_key', true);

        echo "<table class=\"form-table\">";
        echo "<tbody>";
        echo "<tr>";
        echo "<th><label for=\"cpt_title_field\">Title: </label></th>";
        echo "<td><input class=\"regular-text code\" type=\"text\" id=\"cpt_title_field\" name=\"cpt_title_field\" value=\"" . esc_attr($value_title) . "\" /><p>Title slider ( slider 1 )</p></td>";
        echo "</tr>";

        echo "<tr>";
        echo '<th><label for="cpt_description_field">Description: </label></th>';
        echo "<td><textarea name=\"cpt_description_field\" id=\"cpt_description_field\" class=\"large-text code\" rows=\"3\" spellcheck=\"false\">" . esc_attr($value_description) . "</textarea><p>Type your description here !</p></td>";
        echo "</tr>";

        echo "<tbody>";
        echo "</table>";
    }
}
