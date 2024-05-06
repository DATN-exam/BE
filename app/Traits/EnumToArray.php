<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait EnumToArray
{
    public static function getKeys(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_combine(self::getKeys(), self::getValues());
    }

    public static function getValueByKey($key)
    {
        $array = self::toArray();
        return $array[Str::upper($key)] ?? null;
    }

    public static function getKeyByValue($value)
    {
        $array = self::toArray();
        $key = array_search($value, $array);
        return $key !== false ? $key : null;
    }
}
