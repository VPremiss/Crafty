<?php

declare(strict_types=1);

namespace VPremiss\Crafty;

use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Crafty\Support\Concerns\HasValidatedConfigurations;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;
use VPremiss\Crafty\Utilities\Configurated\Support\Events\ConfigurationDispatchedEvent;

class CraftyServiceProvider extends PackageServiceProvider implements Configurated
{
    use HasValidatedConfigurations;

    public $allConfigValidations = [];

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

    public function bootingPackage()
    {
        $this->allConfigValidations += $this->configValidations();

        Event::listen(function (ConfigurationDispatchedEvent $event) {
            foreach ($event->configurations as $name => $path) {
                $this->mergeConfigFrom($path, $name);
            }

            $this->allConfigValidations += $event->configurationValidations;
        });
    }

    public function configValidations(): array
    {
        return [
            'crafty.databasing_chunks_count' => fn () => $this->validateDatabasingChunksCountConfig(),
            'crafty.insertion_default_properties' => fn () => $this->validateInsertionDefaultPropertiesConfig(),
            'crafty.hash_digits_count' => fn () => $this->validateHashDigitsCountConfig(),
            'crafty.string_hash_separator' => fn () => $this->validateStringHashSeparatorConfig(),
        ];
    }
}
