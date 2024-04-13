<?php

namespace VPremiss\Crafty\Concerns;

use VPremiss\Crafty\Exceptions\CraftyConfigurationException;

trait CraftilyHandlesGeneration
{
    public static function uniquelySuffixed(string $string): string
    {
        $separator = config('crafty.string_hash_separator');

        if (!is_string($separator) || empty($separator)) {
            throw new CraftyConfigurationException("String-hash separator must be a filled string.");
        }

        return $string . $separator . unique_meta_hashing_number($string);
    }
}
