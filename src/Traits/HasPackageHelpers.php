<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Traits;

use ReflectionClass;

// ? A package-tools service provider's
trait HasPackageHelpers
{
    public function packageNamespace(): string
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }
    
    public function packageShortName(): string
    {
        return $this->package->shortName();
    }

    public function packagePublishes(array $paths, string $tag): void
    {
        $this->publishes($paths, $tag);
    }
}
