<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use VPremiss\Crafty\CraftyServiceProvider;

trait CraftilyHandlesGeneration
{
    public function uniquelyMetaHashSuffixed(string $string): string
    {
        $separator = validated_config(CraftyServiceProvider::class, 'crafty.string_hash_separator');

        return $string . $separator . unique_meta_hashing_number($string);
    }
}
