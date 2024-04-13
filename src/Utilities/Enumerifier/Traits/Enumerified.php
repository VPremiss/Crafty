<?php

namespace VPremiss\Crafty\Support\Enumerifier\Traits;

use VPremiss\Crafty\Support\Enumerifier\Exceptions\NotAnEnumException;
use VPremiss\Crafty\Support\Enumerifier\Exceptions\DuplicateEnumsException;
use VPremiss\Crafty\Support\Enumerifier\Exceptions\ExcludedAllEnumsException;
use VPremiss\Crafty\Support\Enumerifier\Exceptions\InsufficientAmountException;
use VPremiss\Crafty\Support\Enumerifier\Exceptions\NoEnumCasesFoundException;
use Illuminate\Support\Collection;

trait Enumerified
{
    public static function count(): int
    {
        self::checkForCases();

        return count(self::cases());
    }

    public static function first(): self
    {
        self::checkForCases();

        return self::cases()[0];
    }

    public static function random(int $amount = 1, self|array $exceptFor = [], bool $asArray = false): self|array
    {
        if ($amount < 1) {
            throw new InsufficientAmountException('The amount should be at least one.');
        }
        
        self::checkForCases();

        $exceptFor = [...$exceptFor];
        $exceptFor = self::validatedEnums($exceptFor);

        return collect(self::cases())
            ->reject(fn ($case) => in_array($case, $exceptFor))
            ->random($amount > 1 ? min($amount, self::count()) : ($asArray ? $amount : null));
    }

    public static function names(self|array $exceptFor = []): array
    {
        self::checkForCases();

        $exceptFor = [...$exceptFor];
        $exceptFor = self::validatedEnums($exceptFor);

        return collect(self::cases())
            ->reject(fn ($case) => in_array($case, $exceptFor))
            ->map(fn ($item) => $item->name)
            ->toArray();
    }

    public static function values(self|array $exceptFor = []): array
    {
        self::checkForCases();

        $exceptFor = [...$exceptFor];
        $exceptFor = self::validatedEnums($exceptFor);

        return collect(self::cases())
            ->reject(fn ($case) => in_array($case, $exceptFor))
            ->map(fn ($item) => $item->value)
            ->toArray();
    }

    public static function valuesAsString(self|array $exceptFor = []): string
    {
        self::checkForCases();

        $exceptFor = [...$exceptFor];
        $exceptFor = self::validatedEnums($exceptFor);

        return collect(self::cases())
            ->reject(fn ($case) => in_array($case, $exceptFor))
            ->pluck('value')
            ->implode(',');
    }

    public static function collection(self|array $exceptFor = [], $isTranslated = true): Collection
    {
        self::checkForCases();

        $exceptFor = [...$exceptFor];
        $exceptFor = self::validatedEnums($exceptFor);

        return collect(self::cases())
            ->map(fn ($item) => [$isTranslated ? $item->translated() : $item->name => $item->value])
            ->collapse();
    }

    private static function checkForCases(): void
    {
        if (empty(self::cases())) {
            throw new NoEnumCasesFoundException('No enum cases were found!');
        }
    }

    private static function validatedEnums(array $enums): array
    {
        foreach ($enums as $enum) {
            if (!$enum instanceof self) {
                throw new NotAnEnumException('Not an enum instance was detected.');
            }
        }

        $enumValues = array_map(fn ($enum) => $enum->value, $enums);
        if (count($enumValues) !== count(array_unique($enumValues))) {
            throw new DuplicateEnumsException('Duplicate enum cases were found.');
        }

        $allCasesValues = array_map(fn ($case) => $case->value, self::cases());
        if (count($enumValues) === count($allCasesValues) && !array_diff($allCasesValues, $enumValues)) {
            throw new ExcludedAllEnumsException('All possible enum cases were excluded!');
        }

        return $enums;
    }
}
