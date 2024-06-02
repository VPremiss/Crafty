<?php

use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\Crafty\Support\Exceptions\CraftyFunctionException;

// * ===========
// * Validation
// * =========

if (function_exists('is_filled_string')) {
    throw new CraftyFunctionException('The crafty function "is_filled_string()" already exists!');
} else {
    function is_filled_string($value): bool
    {
        return is_string($value) && filled($value);
    }
}

if (function_exists('is_associative_array')) {
    throw new CraftyFunctionException('The crafty function "is_associative_array()" already exists!');
} else {
    function is_associative_array(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}

if (function_exists('is_enum')) {
    throw new CraftyFunctionException('The crafty function "is_enum()" already exists!');
} else {
    function is_enum(mixed $enum, ?string $namespace = null): bool
    {
        if (!is_object($enum)) {
            return false;
        }

        $isEnum = enum_exists(get_class($enum));

        if ($namespace !== null) {
            return $isEnum && get_class($enum) === $namespace;
        }

        return $isEnum;
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
    throw new CraftyFunctionException('The crafty function "unique_meta_hashing_number()" already exists!');
} else {
    function unique_meta_hashing_number(string $string, ?int $digits = null): string
    {
        $digits ??= CraftyPackage::getConfiguration('crafty.hash_digits_count');

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
