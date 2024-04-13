<?php

namespace VPremiss\Crafty\Support\Enumerifier\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnumsArray implements ValidationRule
{
    public function __construct(
        protected string $enumClass,
        protected int $itemMaxLength,
    ) {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value) || ! filled($value)) {
            $fail('The :attribute must be a filled array.');

            return;
        }

        if (! is_enum($firstEnum = $value[0])) {
            $fail('The :attribute must be an array of enums.');

            return;
        }

        if (count($value) > ($enumCount = count($firstEnum::cases()))) {
            $fail("The :attribute items must be not be more than its enum cases: ($enumCount).");

            return;
        }

        foreach ($value as $index => $item) {
            $values = collect($this->enumClass::cases())
                ->pluck('value')
                ->toArray();

            if (! in_array($item, $values)) {
                $fail("Item ({$index}) in :attribute must be of type '{$this->enumClass}' enum.");

                return;
            }

            if (strlen($item) > $this->itemMaxLength) {
                $fail("Item ({$index}) in :attribute must not be longer than '{$this->itemMaxLength}' characters.");

                return;
            }
        }
    }
}
