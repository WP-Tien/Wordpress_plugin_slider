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

        // Save metabox
        add_action('save_post', [$this, 'save_meta_cpt_add_new'], 10, 2);
    }

    // ------------------------------------------------------------------
    // ------------------------ META BOX --------------------------------
    // ------------------------------------------------------------------
    public function init_metabox_load_post_new()
    {
        add_action('add_meta_boxes', [$this, 'cpt_add_slider_meta_box']);
    }

    public function init_metabox_load_post()
    {
        add_action('add_meta_boxes', [$this, 'cpt_add_slide_meta_box']);
    }

    public function cpt_add_slider_meta_box()
    {
        add_meta_box('slide_info', 'Main Slider Setting', [$this, 'cpt_setting_callback'], 'cpt-slider', 'advanced', 'high');
    }

    public function cpt_add_slide_meta_box()
    {
        add_meta_box('title_slide', 'Slide information', [$this, 'cpt_setting_title_callback'], 'cpt-slider', 'side');

        add_meta_box('child_slide_info', 'Click to edit your slide', [$this, 'cpt_setting_slide_callback'], 'cpt-slider', 'advanced', 'high');
    }

    // ------------------------------------------------------------------
    // ------------------------- CALL-BACK ------------------------------
    // ------------------------------------------------------------------
    public function cpt_setting_callback($post)
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

    public function cpt_setting_title_callback($post)
    {
        wp_nonce_field('cpt_slider_data', 'cpt_slider_meta_box_nonce');

        $value_title = get_post_meta($post->ID, '_title_slider_value_key', true);
        $value_description = get_post_meta($post->ID, '_description_slider_value_key', true);

        echo "<div class=\"label-slide-title\"><label for=\"cpt_title_field\">Title: </label></div>";
        echo "<div><input class=\"code input-slide-title\" type=\"text\" id=\"cpt_title_field\" name=\"cpt_title_field\" value=\"" . esc_attr($value_title) . "\" /></div>";

        echo "<br>";

        echo "<div class=\"label-slide-title\"><label for=\"cpt_description_field\">Description: </label></div>";
        echo "<div><textarea name=\"cpt_description_field\" id=\"cpt_description_field\" class=\"large-text code\" rows=\"3\" spellcheck=\"false\">" . esc_attr($value_description) . "</textarea></div>";
    }

    public function cpt_setting_slide_callback($post)
    {
        $args = array(
            'post_type' => 'cpt-per-slide',
            'post_parent' => $post->ID,
            'order' => 'ASC',
            'posts_per_page' => -1
        );

        $query = new \WP_Query($args);
?>
        <ul class="thumbs-wrapper" data-id-parent="<?php echo esc_attr($post->ID); ?>">
            <?php
            if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
            ?>
                    <li data-id="<?php the_ID(); ?>" class="slide <?php echo "slide-" . get_the_ID(); ?>">
                        <div class="delete-btn"></div>
                        <img src="<?php echo esc_url(get_the_excerpt()); ?>" height="75" width="138">
                    </li>
            <?php
                endwhile;
            endif;
            wp_reset_postdata();
            ?>
            <li class="add-new-btn selected">
                <div>
                    <p> Add New </p>
                </div>
            </li>
        </ul>

        <div class="clear"></div>

        <div class="slides-options">
            <div class="slide-opt">
                <div class="backend-option">
                    <div class="backend-option-label">
                        <label>Image</label>
                    </div>
                    <div class="backend-option-input">
                        <div id="thumb" data-image="<?php echo NHTSLIDER_URL . 'images/no-image.png' ?>">
                            <img src="<?php echo NHTSLIDER_URL . 'assets/admin/images/no-image.png' ?>" style="background-image: url(<?php echo NHTSLIDER_URL . 'assets/admin/images/no-image.png'; ?>)" alt="">
                            <a href="#"> </a>
                        </div>
                    </div>
                </div>
                <div class="backend-option">
                    <div class="backend-option-label">
                        <label>Title</label>
                    </div>
                    <div class="backend-option-input">
                        <input name="" id="title-slide" class="" value="" type="text">
                    </div>
                </div>
                <div class="backend-option">
                    <div class="backend-option-label">
                        <label>Description</label>
                    </div>
                    <div class="backend-option-input">
                        <textarea rows="6" name="" id="description-slide" class="code" spellcheck="false"></textarea>
                    </div>
                </div>
                <div class="buttons-wrapper">
                    <?php wp_nonce_field('slider_nonce', 'ajax-slider-nonce'); ?>
                    <button id="add-slide" class="button-primary button-large">Add Slide</button>
                    <button id="update-slide" data-id='' class="edit-buttons button-primary button-large" style="display: none;"> Save slide </button>
                    <button id="cancel-slide" class="edit-buttons button">Cancel</button>
                </div>
            </div>
        </div>
<?php
    }

    // ------------------------------------------------------------------
    // ------------------------- SAVE META ------------------------------
    // ------------------------------------------------------------------
    public function save_meta_cpt_add_new($post_id)
    {
        if (!isset($_POST['cpt_slider_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['cpt_slider_meta_box_nonce'], 'cpt_slider_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $title_field = sanitize_text_field($_POST['cpt_title_field']);
        $description_field = sanitize_text_field($_POST['cpt_description_field']);

        update_post_meta($post_id, '_title_slider_value_key', $title_field);
        update_post_meta($post_id, '_description_slider_value_key', $description_field);

        // ! ;) https://developer.wordpress.org/reference/functions/wp_update_post/#user-contributed-notes
        if (!wp_is_post_revision($post_id)) {
            // unhook this function so it doesn't loop infinitely
            remove_action('save_post', [$this, 'save_meta_cpt_add_new']);

            $post = array(
                'ID' => $post_id,
                'post_title' => 'slider_' . $post_id,
                'post content' => 'slider_info_' . $post_id
            );

            wp_update_post($post);

            add_action('save_post', [$this, 'save_meta_cpt_add_new']);
        }
    }
}
