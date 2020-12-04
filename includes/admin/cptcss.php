<?php

namespace NHTSlider\Includes\Admin;

/**
 * Custom post type css
 */

class CptCss
{
    public function __construct()
    {
        add_action('admin_head', [$this, 'cpt_css']);
        add_action('post_submitbox_misc_actions', [$this, 'cpt_post_type_info']);
    }

    public function cpt_css()
    {
        global $post_type;

        if ($post_type == 'cpt-slider') {
            echo '
            <style type="text/css">
                #misc-publishing-actions,
                #minor-publishing-actions{
                    display:none;
                }
                #post-body-content {
                    margin-bottom: 0px!important;
                }
                #post-body #normal-sortables {
                    min-height: 0px;
                }
            </style>
        ';
        }
    }

    function cpt_post_type_info($post)
    {
        $post_type = 'cpt-slider';

        if ($post_type == $post->post_type) {
            // echo  "<div class=\"misc-pub-section\">
            //     <b> Remember: </b>
            //     <i> You must save the slide before click publish. </i>
            // </div>";
        }
    }
}
