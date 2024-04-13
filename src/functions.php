<?php

use VPremiss\Crafty\Exceptions\CraftyConfigurationException;
use VPremiss\Crafty\Exceptions\CraftyFunctionExistsException;

// * ===========
// * Validation
// * =========

if (function_exists('is_enum')) {
    throw new CraftyFunctionExistsException('The crafty function "is_enum()" already exists!');
} else {
    function is_enum(mixed $enum): bool
    {
        if (!is_object($enum)) {
            return false;
        }

        return enum_exists(get_class($enum));
    }
}

// ...

// * ============
// * Performance
// * ==========

// ...

// * ===========
// * Generation
// * =========

if (function_exists('unique_meta_hashing_number')) {
    throw new CraftyFunctionExistsException('The crafty function "unique_meta_hashing_number()" already exists!');
} else {
    function unique_meta_hashing_number(string $string, ?int $digits = null): string
    {
        $digits ??= config('crafty.hash_digits_count', 8);

        if (intval($digits) < 8) {
            throw new CraftyConfigurationException(
                "Requested $digits digits for general meta hashing generation, which is too low to maintain uniqueness in identifiers. Please use at least 8 digits."
            );
        }

        $uniqueString = $string . uniqid() . rand(100, 999);
        $hash = md5($uniqueString);
        $numericHash = hexdec(substr($hash, 0, $digits)) % 100000000;
        $shortHash = str_pad((string)$numericHash, $digits, '0', STR_PAD_LEFT);

        return $shortHash;
    }
}

// ...

// * =============
// * Manipulation
// * ===========

// ...
