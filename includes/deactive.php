<?php

namespace NHTSlider\Includes;

class Deactive
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
