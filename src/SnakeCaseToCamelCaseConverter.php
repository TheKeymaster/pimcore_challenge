<?php

namespace App;

class SnakeCaseToCamelCaseConverter
{
    public static function convert(string $input, bool $uppercaseFirstCharacter = false): string
    {
        $str = str_replace('_', '', ucwords($input, '_'));

        return $uppercaseFirstCharacter ? ucfirst($str) : lcfirst($str);
    }
}
