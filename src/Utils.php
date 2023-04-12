<?php

namespace App;

class Utils
{
    public static function isAssociativeArray($array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
