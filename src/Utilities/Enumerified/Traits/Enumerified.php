<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Enumerified\Traits;

use Illuminate\Support\Collection;
use VPremiss\Crafty\Utilities\Enumerified\Support\Exceptions\EnumerifiedDuplicateEnumsException;
use VPremiss\Crafty\Utilities\Enumerified\Support\Exceptions\EnumerifiedExcludedAllEnumsException;
use VPremiss\Crafty\Utilities\Enumerified\Support\Exceptions\EnumerifiedInsufficientAmountException;
use VPremiss\Crafty\Utilities\Enumerified\Support\Exceptions\EnumerifiedNoEnumCasesFoundException;
use VPremiss\Crafty\Utilities\Enumerified\Support\Exceptions\EnumerifiedNotAnEnumException;

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
            throw new EnumerifiedInsufficientAmountException('The amount should be at least one.');
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
            throw new EnumerifiedNoEnumCasesFoundException('No enum cases were found!');
        }
    }

    private static function validatedEnums(array $enums): array
    {
        foreach ($enums as $enum) {
            if (!$enum instanceof self) {
                throw new EnumerifiedNotAnEnumException('Not an enum instance was detected.');
            }
        }

        $enumValues = array_map(fn ($enum) => $enum->value, $enums);
        if (count($enumValues) !== count(array_unique($enumValues))) {
            throw new EnumerifiedDuplicateEnumsException('Duplicate enum cases were found.');
        }

        $allCasesValues = array_map(fn ($case) => $case->value, self::cases());
        if (count($enumValues) === count($allCasesValues) && !array_diff($allCasesValues, $enumValues)) {
            throw new EnumerifiedExcludedAllEnumsException('All possible enum cases were excluded!');
        }

        return $enums;
    }
}
