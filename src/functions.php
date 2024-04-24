<?php

use VPremiss\Crafty\CraftyServiceProvider;
use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\Crafty\Support\Exceptions\CraftyFunctionDoesNotExistException;

// * ===========
// * Validation
// * =========

if (function_exists('is_enum')) {
    throw new CraftyFunctionDoesNotExistException('The crafty function "is_enum()" already exists!');
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
    throw new CraftyFunctionDoesNotExistException('The crafty function "unique_meta_hashing_number()" already exists!');
} else {
    function unique_meta_hashing_number(string $string, ?int $digits = null): string
    {
        $digits ??= CraftyPackage::validatedConfig('crafty.hash_digits_count', CraftyServiceProvider::class);

        $uniqueString = $string . uniqid() . rand(100, 999);
        $hash = md5($uniqueString);
        $numericHash = hexdec(substr($hash, 0, $digits)) % 100000000;
        $shortHash = str_pad((string) $numericHash, $digits, '0', STR_PAD_LEFT);

        return $shortHash;
    }
}

// ...

// * =============
// * Manipulation
// * ===========

// ...
