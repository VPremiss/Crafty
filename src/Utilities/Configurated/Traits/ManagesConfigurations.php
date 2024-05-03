<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Configurated\Traits;

use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Support\Arr;
use VPremiss\Crafty\CraftyPackage;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;

// ? A package-tools service provider's
trait ManagesConfigurations
{
    // ? Apply in the packageRegistered method
    public function registerConfigurations(): void
    {
        if (!$this instanceof Configurated) {
            throw new ConfiguratedValidatedConfigurationException(
                "The package service provider where ManagesConfigurations is used, does not implement the 'Configurated' interface."
            );
        }

        $this->app->singleton(CraftyPackage::class, fn ($_) => new CraftyPackage());

        // ? Saves configurations
        foreach ($this->getPackageConfigurationFiles() as $name => $path) {
            $this->mergeConfigFileIntoRegistry($name, $path);
        }

        // ? Saves their validations
        foreach (Arr::dot($this->configurationValidations()) as $key => $closure) {
            app(CraftyPackage::class)->setConfigurationValidation($key, $closure);
        }
    }

    protected function getPackageConfigurationFiles(): array
    {
        $configurationsFiles = [];

        foreach ($this->package->configFileNames as $configFileName) {
            $configurationsFiles[$configFileName] = $this->package->basePath("/../config/{$configFileName}.php");
        }

        return $configurationsFiles;
    }

    protected function mergeConfigFileIntoRegistry(string $name, string $path): void
    {
        if (!($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $configArray = require $path;
            $existingConfig = app(CraftyPackage::class)->getConfiguration($name, []);
            $mergedConfig = Arr::dot(array_merge(Arr::undot($existingConfig), Arr::undot($configArray)));

            app(CraftyPackage::class)->setConfiguration($name, Arr::undot($mergedConfig));
        }
    }
}
