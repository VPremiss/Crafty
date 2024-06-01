<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnumsArray implements ValidationRule
{
    public function __construct(
        protected string $enumClass,
        protected ?int $valueMaxLength = null,
    ) {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value) || !filled($value)) {
            $fail('The :attribute must be a filled array.');
            return;
        }

        if (!is_enum($firstEnum = $value[0])) {
            $fail('The :attribute must be an array of enums.');
            return;
        }

        if (count($value) > ($enumCount = count($firstEnum::cases()))) {
            $fail("The :attribute items must be not be more than its enum cases: ($enumCount).");
            return;
        }

        foreach ($value as $index => $item) {
            if (!is_enum($item)) {
                $fail("Item ({$index}) in :attribute must be of type '{$this->enumClass}' enum.");
                return;
            }

            if (!is_null($this->valueMaxLength) && (strlen($item->value()) > $this->valueMaxLength)) {
                $fail(
                    "The enum value of the index ({$index}) has longer characters than the maximum allowed ({$this->valueMaxLength}) for :attribute input."
                );
                return;
            }
        }
    }
}
