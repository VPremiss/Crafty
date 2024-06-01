<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Enums;

use Closure;
use VPremiss\Crafty\ExtendedEnums\ExtendedSpecificEnumDataType;
use VPremiss\Crafty\Support\Interfaces\ValidatedDataType;

enum DataType: string implements ValidatedDataType
{
    case Boolean = 'boolean';
    case Integer = 'integer';
    case Float = 'float';
    case String = 'string';
    case FilledString = 'filled-string';
    case Enum = 'enum';
    case Closure = 'closure';
    case Array = 'array';

    public function isValidData($data): bool
    {
        return match ($this) {
            self::Boolean => is_bool($data),
            self::Integer => is_int($data),
            self::Float => is_float($data),
            self::String => is_string($data),
            self::FilledString => is_string($data) && filled($data),
            self::Enum => is_enum($data),
            self::Closure => $data instanceof Closure,
            self::Array => is_array($data),
        };
    }

    public static function SpecificEnum(string $namespace): ExtendedSpecificEnumDataType
    {
        return new ExtendedSpecificEnumDataType($namespace);
    }
}
