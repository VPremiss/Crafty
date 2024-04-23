<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Configurated\Interfaces;

interface Configurated
{
    public function configDefault(string $configKey): mixed;

    public function configValidation(string $configKey): void;
}
