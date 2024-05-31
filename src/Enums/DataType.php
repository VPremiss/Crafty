<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Enums;

use Closure;

enum DataType: string
{
    case Boolean = 'boolean';
    case Integer = 'integer';
    case Float = 'float';
    case String = 'string';
    case Closure = 'closure';
    case Array = 'array';

    public function isValidData($data): bool
    {
        return match ($this) {
            self::Boolean => is_bool($data),
            self::Integer => is_int($data),
            self::Float => is_float($data),
            self::String => is_string($data),
            self::Closure => $data instanceof Closure,
            self::Array => is_array($data),
        };
    }
}
