<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Installable\Interfaces;

// ? A package-tools service provider's
interface Installable
{
    public function seederFilePaths(): array;
}
