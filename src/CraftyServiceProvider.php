<?php

namespace VPremiss\Crafty;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Crafty\Support\Concerns\HasValidatedConfiguration;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;

class CraftyServiceProvider extends PackageServiceProvider implements Configurated
{
    use HasValidatedConfiguration;

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

    public function configDefault(string $configKey): mixed
    {
        return match ($configKey) {
            'crafty.databasing_chunks_count' => 500,
            'crafty.insertion_default_properties' => [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            'crafty.hash_digits_count' => 8,
            'crafty.string_hash_separator' => ' #',
        };
    }

    public function configValidation(string $configKey): void
    {
        match ($configKey) {
            'crafty.databasing_chunks_count' => $this->validateDatabasingChunksCountConfig(),
            'crafty.insertion_default_properties' => $this->validateInsertionDefaultPropertiesConfig(),
            'crafty.hash_digits_count' => $this->validateHashDigitsCountConfig(),
            'crafty.string_hash_separator' => $this->validateStringHashSeparatorConfig(),
        };
    }
}
