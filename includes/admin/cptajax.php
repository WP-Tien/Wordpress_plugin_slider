<?php

namespace NHTSlider\Includes\Admin;

/**
 * Ajax handler
 */
class CptAjax
{

    public function __construct()
    {
        // Ajax for selecting the slide
        add_action('wp_ajax_nopriv_select_side_cpt', [$this, 'select_slide_cpt']);
        add_action('wp_ajax_select_side_cpt', [$this, 'select_slide_cpt']);

        // Ajax for adding the slide
        add_action('wp_ajax_nopriv_add_slide_cpt', [$this, 'add_slide_cpt']);
        add_action('wp_ajax_add_slide_cpt', [$this, 'add_slide_cpt']);

        // Ajax for deleting the slide
        add_action('wp_ajax_nopriv_del_slide_cpt', [$this, 'del_slide_cpt']);
        add_action('wp_ajax_del_slide_cpt', [$this, 'del_slide_cpt']);
    }

    public function select_slide_cpt()
    {
        if (!$_POST['id']) die();

        $post_info = get_post($_POST['id'], ARRAY_A);

        echo json_encode($post_info, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

        die();
    }

    public function add_slide_cpt()
    {
        if (!$_POST) die();

        if (!isset($_POST['nonce'])) die();

        if (!wp_verify_nonce($_POST['nonce'], 'slider_nonce')) die();

        // DOING_AJAX
        if (!is_admin() && !defined('DOING_AJAX')) die();

        $args = [
            'post_title' => wp_strip_all_tags($_POST['title']),
            'post_content' => wp_strip_all_tags($_POST['description']),
            'post_parent'  => wp_strip_all_tags($_POST['idParent']),
            'post_excerpt' => wp_strip_all_tags($_POST['img']),
            'post_type' => 'cpt-per-slide',
            'post_status' => 'publish'
        ];

        $post_id = wp_insert_post($args);

        if ($post_id) {
            $post_info = get_post($post_id, ARRAY_A);

            echo json_encode($post_info, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        }

        die();
    }

    public function del_slide_cpt()
    {
        if (!$_POST['id']) die();

        // DOING_AJAX
        if (!is_admin() && !defined('DOING_AJAX')) die();

        $result = wp_delete_post(wp_strip_all_tags($_POST['id']), true);

        if ($result == false || !isset($result)) { // false or null on failure.
            die();
        }

        echo $result->ID;

        die();
    }
}
