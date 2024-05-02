<?php

declare(strict_types=1);

use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

it('can store configuration values through keys and suppots key-dotting', function () {
    $key = 'some-package.some-array.some-inner-value';
    $value = ['anything', 'really'];

    CraftyPackage::setConfiguration($key, $value);

    expect(CraftyPackage::getConfiguration($key))->toBe($value);
});

it('can also validate configuration values through keys and suppots key-dotting too', function () {
    $key = 'some-package.some-array.some-inner-value';

    CraftyPackage::setConfiguration($key, 1);

    function validateSomeConfig($value)
    {
        if ($value <= 2) throw new ConfiguratedValidatedConfigurationException('Config value should be greater than 2');
    }

    $closure = fn ($value) => validateSomeConfig($value);

    CraftyPackage::setConfigurationValidation($key, $closure);

    expect(CraftyPackage::getConfiguration($key))->toThrow(ConfiguratedValidatedConfigurationException::class);
    expect(CraftyPackage::setConfiguration($key, 2))->toThrow(ConfiguratedValidatedConfigurationException::class);
    expect(CraftyPackage::setConfiguration($key, 4))->not->toThrow(ConfiguratedValidatedConfigurationException::class);
})->throws(ConfiguratedValidatedConfigurationException::class);
