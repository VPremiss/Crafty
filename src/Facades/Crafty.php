<?php

namespace VPremiss\Crafty\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VPremiss\Crafty\Crafty
 */
class Crafty extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Crafty\Crafty::class;
    }
}
