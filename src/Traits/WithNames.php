<?php

namespace xGrz\PayU\Traits;


use xGrz\PayU\Exceptions\StatusNameException;

trait WithNames
{

    /**
     * @throws StatusNameException
     */
    public static function findByName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === strtoupper($name)) return $case;
        }
        throw new StatusNameException("Unknown status name: `$name`");
    }

    public static function names(): array
    {
        $names = [];
        foreach (self::cases() as $case) {
            $names[] = $case->name;
        }
        return $names;
    }


}
