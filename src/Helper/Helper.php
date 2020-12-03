<?php

namespace MiladZamir\Sledge\Helper;

class Helper
{
    public static function createUniqueString($length)
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    public static function getActionStatus($url, $text)
    {
        if (strpos($url, $text) !== false) {
            return true;
        }else{
            return false;
        }
    }
}
