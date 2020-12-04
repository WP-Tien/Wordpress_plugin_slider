<?php

namespace NHTSlider\Includes;

class Active
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
