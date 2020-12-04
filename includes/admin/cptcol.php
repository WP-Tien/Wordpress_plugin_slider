<?php

namespace NHTSlider\Includes\Admin;

/**
 * Custom post type column customize.
 */
class CptCol
{
    public function __construct()
    {
        add_filter('manage_cpt-slider_posts_columns', [$this, 'cpt_set_columns']);
        add_action('manage_cpt-slider_posts_custom_column', [$this, 'cpt_custom_column'], 10, 2);
    }

    public function cpt_set_columns($col)
    {
        $newColumns = array();
        $newColumns['cb'] = 'Bulk action';
        // $newColumns['slider-image'] = 'Slider Image';
        $newColumns['title-slide'] = 'Title';
        $newColumns['description'] = 'Description';
        $newColumns['count'] = 'Number of Images';
        $newColumns['shortcode'] = 'Shortcode';
        return $newColumns;
    }

    public function cpt_custom_column($col, $post_id)
    {
        switch ($col) {
            case 'cb':
                echo '<input type="checkbox" />';
                break;

            case 'title-slide':
                $title = get_post_meta($post_id, '_title_slider_value_key', true);
                echo '<a href="' . get_admin_url() . 'post.php?post=' . $post_id . '&action=edit" >' . $title . '</a>';
                break;

            case 'description':
                $description = get_post_meta($post_id, '_description_slider_value_key', true);
                echo $description;
                break;

            case 'count':
                $args = array(
                    'post_type'  => 'cpt-per-slide',
                    'posts_per_page' => -1,
                    'post_parent'  => $post_id
                );

                $count = count(get_posts($args));

                echo esc_html($count);
                break;

            case 'shortcode':
                echo '[ nht_simple_slide id="' . esc_html($post_id) . '" ]';
                break;
        }
    }
}
