<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

trait CraftilyHandlesSearching
{
    public function findDottedKeyInArray(array $sourceArray, string $key): ?string
    {
        if (isset($sourceArray[$key])) return $key;

        $basePackageName = str($key)->before('.')->value() . '.';

        // ? Reduce the key a bit by bit, until it there's no dots anymore
        while ($key != $basePackageName) {
            $key = str($key)->beforeLast('.')->value();

            if (!str($key)->contains('.')) {
                break;
            }

            // ? Recheck the reduced part
            if (isset($sourceArray[$key])) {
                return $key;
            }
        }

        return null;
    }
}
