<?php

declare(strict_types=1);

namespace App\Helpers;

use Cocur\Slugify\Slugify;

class Slugger
{
    private static ?Slugify $slugify = null;

    /** @var array<string, true> */
    private static array $used = [];

    public static function reset(): void
    {
        self::$used = [];
    }

    public static function unique(string $text): string
    {
        if (self::$slugify === null) {
            self::$slugify = new Slugify(['regexp' => '/[^A-Za-z0-9]+/']);
        }

        $base = self::$slugify->slugify($text);
        if ($base === '') {
            $base = 'item';
        }

        $slug = $base;
        $i = 2;
        while (isset(self::$used[$slug])) {
            $slug = $base . '-' . $i;
            $i++;
        }

        self::$used[$slug] = true;

        return $slug;
    }
}
