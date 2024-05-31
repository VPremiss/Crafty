<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use Closure;
use VPremiss\Crafty\Enums\DataType;
use VPremiss\Crafty\Support\Exceptions\CraftyValidationException;

trait CraftilyHandlesValidation
{
    public function validatedArray(
        array $array,
        DataType $keysOrValuesType,
        Closure|DataType $valuesTypeOrNestedValidator = null,
    ): bool {
        if (!is_array($array)) {
            throw new CraftyValidationException('Array validation helper was not passed an array!');
        }

        // ? Non-associative array validation
        if ($valuesTypeOrNestedValidator === null) {
            if (is_associative_array($array)) {
                throw new CraftyValidationException(
                    "Array validation helper's needs a 3rd argument to deal with an associative array."
                );
            }


            foreach ($array as $value) {
                if (!$keysOrValuesType->isValidData($value)) {
                    return false;
                }
            }
        }
        // ? Associative array validation
        else {
            if (!is_associative_array($array)) {
                throw new CraftyValidationException(
                    "Array validation helper's 3rd argument is supposed to be dealing with an associative array."
                );
            }

            foreach ($array as $key => $value) {
                if (!$keysOrValuesType->isValidData($key)) {
                    return false;
                }

                if ($valuesTypeOrNestedValidator instanceof Closure) {
                    if (!is_array($value) || !$valuesTypeOrNestedValidator($value)) {
                        return false;
                    }
                } else {
                    if (!$valuesTypeOrNestedValidator->isValidData($value)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
