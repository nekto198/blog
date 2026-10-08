<?php

declare(strict_types=1);

namespace App;

final class Config
{
    /** @var array<string, array<string, mixed>> */
    private static array $items = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        [$file, $item] = array_pad(explode('.', $key, 2), 2, null);

        if ($file === null || $file === '') {
            return $default;
        }

        if (!isset(self::$items[$file])) {
            $path = BASE_PATH . '/config/' . $file . '.php';
            if (!is_file($path)) {
                return $default;
            }

            self::$items[$file] = require $path;
        }

        if ($item === null) {
            return self::$items[$file];
        }

        return self::$items[$file][$item] ?? $default;
    }
}
