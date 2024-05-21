<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use VPremiss\Crafty\Utilities\Installable\Interfaces\Installable;
use VPremiss\Crafty\Utilities\Installable\Support\Exceptions\InstallableInterfaceException;

trait HandlesSeeding
{
    public function seed(string $serviceProviderNamespace, string $seederName): void
    {
        $serviceProvider = app()->resolveProvider($serviceProviderNamespace);

        if (!$serviceProvider instanceof Installable) {
            throw new InstallableInterfaceException(
                'The package service provider must implement Crafty\'s Installable interface.'
            );
        }

        $seeders = collect($serviceProvider->seederFilePaths())
            ->map(fn ($path) => [str($path)->after('seeders/')->before('.php')->value() => $path])
            ->toArray();

        // @phpstan-ignore-next-line
        $namespace = $serviceProvider->getPackageNamespace();
        $seederPath = $seeders[$seederName];
        $seederClassName = array_search($seederPath, $seeders);
        $seederClassName = "{$namespace}\\Database\\Seeders\\$seederClassName";

        require_once $seederPath;

        $seeder = new $seederClassName;
        $seeder->run();
    }
}
