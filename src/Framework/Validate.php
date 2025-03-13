<?php

declare(strict_types=1);

namespace Framework;

class Validate
{

    public static function string(string $value, int $min = 1, float $max = INF): bool
    {

        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }

    public static function email(string $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function password_confirmation(string $password, string $password_confirmation)
    {
        return $password === $password_confirmation;
    }
}
