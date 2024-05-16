<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Enumerified\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use VPremiss\Crafty\Utilities\Enumerified\Support\Exceptions\EnumerifiedEnumException;

trait Enumerified
{
    public static function count(): int
    {
        self::validateCases();

        return count(self::cases());
    }

    public static function first(): self
    {
        self::validateCases();

        return self::cases()[0];
    }

    public static function random(
        int $amount = 1,
        self|array $exceptFor = [],
        bool $asArray = false,
        bool $translated = false,
    ): self|array {
        if ($amount < 1) {
            throw new EnumerifiedEnumException('The amount should be at least one.');
        }

        self::validateCases();
        if ($translated) self::validateTranslations();

        $exceptFor = self::filterEnums(Arr::wrap($exceptFor));

        $result = collect(self::cases())
            ->reject(fn ($case) => in_array($case, $exceptFor))
            ->random($amount > 1 ? min($amount, self::count()) : ($asArray ? $amount : null));

        if ($translated) {
            $result = $result instanceof Collection
                ? $result->map(fn ($item) => [$translated ? $item->translated() : $item->name => $item->value])->collapse()
                : [$result->translated() => $result->value];
        }

        // ? Ensure an array
        $result = $result instanceof Collection ? $result->toArray() : $result;
        $result = $asArray ? Arr::wrap($result) : $result;

        return $result;
    }

    public static function names(self|array $exceptFor = [], bool $translated = false): array
    {
        self::validateCases();
        if ($translated) self::validateTranslations();

        $exceptFor = self::filterEnums(Arr::wrap($exceptFor));

        return collect(self::cases())
            ->reject(fn ($case) => in_array($case, $exceptFor))
            ->map(fn ($item) => $translated ? $item->translated() : $item->name)
            ->toArray();
    }

    public static function values(self|array $exceptFor = [], bool $asString = false): array
    {
        self::validateCases();

        $exceptFor = self::filterEnums(Arr::wrap($exceptFor));

        if ($asString) {
            return collect(self::cases())
                ->reject(fn ($case) => in_array($case, $exceptFor))
                ->pluck('value')
                ->implode(',');
        }

        return collect(self::cases())
            ->reject(fn ($case) => in_array($case, $exceptFor))
            ->map(fn ($item) => $item->value)
            ->toArray();
    }

    public static function collection(self|array $exceptFor = [], bool $translated = true): Collection
    {
        self::validateCases();
        if ($translated) self::validateTranslations();

        $exceptFor = self::filterEnums(Arr::wrap($exceptFor));

        return collect(self::cases())
            ->map(fn ($item) => [$translated ? $item->translated() : $item->name => $item->value])
            ->collapse();
    }

    private static function validateCases(): void
    {
        if (empty(self::cases())) {
            throw new EnumerifiedEnumException('No enum cases were found!');
        }
    }

    private static function filterEnums(array $enums): array
    {
        foreach ($enums as $enum) {
            if (!$enum instanceof self) {
                throw new EnumerifiedEnumException("An instance which isn't an enum was detected.");
            }
        }

        $enumValues = array_map(fn ($enum) => $enum->value, $enums);
        if (count($enumValues) !== count(array_unique($enumValues))) {
            throw new EnumerifiedEnumException('Duplicate enum cases were found.');
        }

        $allCasesValues = array_map(fn ($case) => $case->value, self::cases());
        if (count($enumValues) === count($allCasesValues) && !array_diff($allCasesValues, $enumValues)) {
            throw new EnumerifiedEnumException('All possible enum cases were excluded!');
        }

        return $enums;
    }

    protected static function validateTranslations(): void
    {
        if (!method_exists(self::class, 'translated')) {
            throw new EnumerifiedEnumException(
                "There isn't a 'translated' method on the enum that returns a matching case name string. So create one!"
            );
        }
    }
}
