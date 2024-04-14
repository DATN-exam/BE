<?php

namespace App\Traits;

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
}
