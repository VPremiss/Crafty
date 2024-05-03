<?php

declare(strict_types=1);

namespace VPremiss\Crafty;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Crafty\Support\Concerns\HasConfigurationValidations;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;
use VPremiss\Crafty\Utilities\Configurated\Traits\ManagesConfigurations;

class CraftyServiceProvider extends PackageServiceProvider implements Configurated
{
    use ManagesConfigurations;
    use HasConfigurationValidations;

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('crafty')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->registerConfigurations();
    }

    public function configurationValidations(): array
    {
        return [
            'crafty' => [
                'databasing_chunks_count' => fn ($value) => $this->validateDatabasingChunksCountConfig($value),
                'insertion_default_properties' => fn ($value) => $this->validateInsertionDefaultPropertiesConfig($value),
                'hash_digits_count' => fn ($value) => $this->validateHashDigitsCountConfig($value),
                'string_hash_separator' => fn ($value) => $this->validateStringHashSeparatorConfig($value),
            ],
        ];
    }
}
