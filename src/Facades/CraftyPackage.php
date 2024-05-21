<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed getConfiguration(string $key, $default = null)
 * @method static void setConfiguration(string $key, mixed $value)
 * @method static void setConfigurationValidation(string $key, callable $closure)
 * @method static void seed(string $serviceProviderNamespace, string $seederName)
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
