<?php

namespace NHTSlider;

class Autoloader
{
    public function __construct()
    {
        $this->autoload();
    }

    public static function autoload()
    {
        spl_autoload_register(function ($class_name) {
            $path = '';

            $arr_path = Autoloader::handle_directory($class_name);
            // Check if path is `nthslider` 
            if ($arr_path[0] == 'nhtslider') {
                array_splice($arr_path, 0, 2); // clean up

                if (count($arr_path) >= 2) {
                    for ($i = 0; $i < ((count($arr_path)) - 1); $i++) {
                        $path .= DS . $arr_path[$i];
                    }

                    return require_once  NHTSLIDER_INCLUDES . $path . DS .  end($arr_path) . '.php';
                } else {
                    return require_once  NHTSLIDER_INCLUDES . DS .  $arr_path[0] . '.php';
                }
            }
        });
    }

    /**
     * Slice namespace then make string to lower.
     * 
     * @param array |  Array of classes name.
     * @return array | Array of classes name after handle.
     */
    private static function handle_directory(string $class_name)
    {
        $classes_arr = explode('\\', $class_name);
        $classes_filter = array_map(function ($item) {
            return strtolower($item);
        }, $classes_arr);

        return $classes_filter;
    }
}
