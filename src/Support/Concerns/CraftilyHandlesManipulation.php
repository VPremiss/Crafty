<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use VPremiss\Crafty\Support\Enums\EncodingType;

trait CraftilyHandlesManipulation
{
    public function reverseString(string $string, EncodingType $encoding = EncodingType::UTF8): string
    {
        $encodingValue = $encoding->value;
        $result = '';

        for ($i = mb_strlen($string, $encodingValue) - 1; $i >= 0; $i--) {
            $result .= mb_substr($string, $i, 1, $encodingValue);
        }

        return $result;
    }
}
