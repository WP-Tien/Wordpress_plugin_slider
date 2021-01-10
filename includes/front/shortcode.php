<?php

namespace NHTSlider\Includes\Front;

/**
 * Class generate shortcode
 */

class Shortcode
{
    public function __construct()
    {
        add_shortcode('nht_slider', [$this, 'generate_slide_shortcode']);
    }

    public function generate_slide_shortcode($args)
    {
        $args = [
            'post_type' => 'cpt-per-slide',
            'post_parent' => (int) $args['id'],
            'order' => 'ASC',
            'posts_per_page' => -1
        ];

        $query = new \WP_Query($args);

        ob_start();
?>
        <div class="flexslider">
            <ul class="slides">
                <?php
                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                ?>
                        <li>
                            <img src="<?php echo esc_url(get_the_excerpt()); ?>" alt="<?php the_title(); ?>">
                        </li>
                <?php
                    endwhile;
                endif;

                wp_reset_query();
                ?>
            </ul>
        </div>
        <?php

        add_action('wp_footer', function () {
        ?>
            <script>
                jQuery(function($) {
                    // Can also be used with $(document).ready()
                    $(window).load(function() {
                        $('.flexslider').flexslider({
                            animation: "slide"
                        });
                    });
                });
            </script>
<?php
        }, 100);

        return ob_get_clean();
    }
}
