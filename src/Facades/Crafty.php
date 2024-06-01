<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void chunkedDatabaseInsertion(string $tableName, array $dataArrays, \Closure $callback)
 * @method static string uniquelyMetaHashSuffixed(string $string)
 * @method static string reverseString(string $string, \VPremiss\Crafty\Support\Enums\EncodingType $encoding = \VPremiss\Crafty\Support\Enums\EncodingType::UTF8)
 * @method static bool validatedArray(array $array, \VPremiss\Crafty\Enums\DataType $keysOrValuesType, \Closure|\VPremiss\Crafty\Enums\DataType|null $valuesTypeOrNestedValidator = null)
 *
 * @see \VPremiss\Crafty\Crafty
 */
class Crafty extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Crafty\Crafty::class;
    }
}
