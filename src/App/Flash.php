<?php

namespace App;


class Flash
{
    public static function add($key, $value)
    {
        if (! isset($_SESSION['flash_data'])) {

            $_SESSION['flash_data'] = [];
        }

        $_SESSION['flash_data'][$key] = $value;
    }

    public static function get($key)
    {
        if (isset($_SESSION['flash_data'][$key])) {

            $value = $_SESSION['flash_data'][$key];

            unset($_SESSION['flash_data'][$key]);

            return $value;
        }
    }

    public static function has($key)
    {
        return isset($_SESSION['flash_data'][$key]);
    }
}
