<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed validatedConfig(string $configKey, string $packageServiceProviderNamespace)
 * @method static mixed config(string $configKey, object $packageServiceProvider)
 *
 * @see \VPremiss\Crafty\CraftyPackage
 */
class CraftyPackage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Crafty\CraftyPackage::class;
    }
}
