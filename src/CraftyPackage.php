<?php

declare(strict_types=1);

namespace VPremiss\Crafty;

use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;

class CraftyPackage
{
    public function validatedConfig(string $configKey, string $packageServiceProviderNamespace): mixed
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

        if (is_null($validation = $this->getConfigValidation($configKey)) || !is_callable($validation)) {
            throw new ConfiguratedValidatedConfigurationException(
                'No closure was found to handle this config key among configValidations array values.'
            );
        }

        call_user_func($validation);

        return config($configKey);
    }

    protected function getConfigValidation(string $configKey): ?callable
    {
        /** @var CraftyServiceProvider $crafty */
        $crafty = app()->resolveProvider(CraftyServiceProvider::class);

        $basePackageName = str($configKey)->before('.')->value() . '.';

        while ($configKey != $basePackageName) {
            if (isset($crafty->allConfigValidations[$configKey])) {
                return $crafty->allConfigValidations[$configKey];
            }

            $configKey = str($configKey)->beforeLast('.')->value();

            if (!str($configKey)->contains('.')) {
                $configKey = $basePackageName;
            }
        }

        return null;
    }
}
