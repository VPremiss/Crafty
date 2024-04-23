<?php

use VPremiss\Crafty\CraftyServiceProvider;
use VPremiss\Crafty\Exceptions\CraftyFunctionDoesNotExistException;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;

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

if (function_exists('validated_config')) {
    throw new CraftyFunctionDoesNotExistException('The crafty function "validated_config()" already exists!');
} else {
    function validated_config(string $packageServiceProviderNamespace, string $configKey): mixed
    {
        if (!filled($packageServiceProviderNamespace) && !class_exists($packageServiceProviderNamespace)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Package service provider namespace is not pointing to an existing class.'
            );
        }

        $packageServiceProvider = app()->resolveProvider($packageServiceProviderNamespace);

        if (!$packageServiceProvider instanceof Configurated) {
            throw new ConfiguratedValidatedConfigurationException(
                "Package service provider class does not implement the 'Configurated' interface."
            );
        }

        if (empty($configKey)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The config key is empty.'
            );
        }

        try {
            $packageServiceProvider->configValidation($configKey);
        } catch (UnhandledMatchError) {
            throw new ConfiguratedValidatedConfigurationException(
                'The config key is not handled among configValidation() match cases.'
            );
        }

        try {
            $default = $packageServiceProvider->configDefault($configKey);
        } catch (UnhandledMatchError) {
            throw new ConfiguratedValidatedConfigurationException(
                'The config key is not handled among configDefault() match cases.'
            );
        }

        return config($configKey, $default);
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
        $digits ??= validated_config(CraftyServiceProvider::class, 'crafty.hash_digits_count');

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
