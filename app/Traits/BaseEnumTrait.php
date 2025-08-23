<?php

namespace App\Traits;

trait BaseEnumTrait
{
    public function probertyName(): string
    {
        $res = str_replace('_', ' ', $this->name);
        return ucwords(strtolower($res));
    }

    public function returnName(): string
    {
        $res = str_replace('_', ' ', $this->name);
        return ucwords($res);
    }

    public static function tryFromName(string $name): ?object
    {
        return defined("self::$name") ? constant("self::$name") : null;
    }
}

