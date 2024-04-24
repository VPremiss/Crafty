<?php

declare(strict_types=1);

namespace VPremiss\Crafty;

use UnhandledMatchError;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;

class CraftyPackage
{
    public function validatedConfig(string $packageServiceProviderNamespace, string $configKey): mixed
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
            $packageName = str($configKey)->before('.')->value();
            $mainKey = str($configKey)->after('.')->before('.')->value();

            $packageServiceProvider->configValidation("$packageName.$mainKey");
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

    public function config(object $packageServiceProvider, string $configKey): mixed
    {
        return config($configKey, $packageServiceProvider->configDefault($configKey));
    }
}
