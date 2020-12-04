<?php

namespace NHTSlider\Includes\Front;

class Front
{
    public static function test()
    {
        echo "<pre style='margin-left: 250px'>";
        print_r('front');
        echo "</pre>";
    }
}

if (!is_admin()) {
    Front::test();
}
