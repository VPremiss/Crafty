<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Interfaces;

interface ValidatedDataType
{
    public function isValidData($data): bool;
}
