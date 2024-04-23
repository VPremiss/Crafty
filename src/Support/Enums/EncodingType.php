<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Enums;

enum EncodingType: string
{
    case ASCII = 'ASCII';
    case UTF8 = 'UTF-8';
    case CP850 = 'CP850';
    case Binary = 'BINARY';
}
