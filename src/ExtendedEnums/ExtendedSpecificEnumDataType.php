<?php

declare(strict_types=1);

namespace VPremiss\Crafty\ExtendedEnums;

use VPremiss\Crafty\Support\Interfaces\ValidatedDataType;

final class ExtendedSpecificEnumDataType implements ValidatedDataType
{
    public function __construct(
        private string $namespace,
        public string $name = 'SpecificEnum',
        public string $value = 'specific-enum',
    ) {
    }

    public function isValidData($data): bool
    {
        return is_enum($data, $this->namespace);
    }
}
