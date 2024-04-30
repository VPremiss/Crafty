<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Configurated\Interfaces;

use Closure;

interface Configurated
{
    /**
     * It's must return an array of configuration keys pointing towards their
     * validation methods.
     *
     * @return array<string, Closure>
     */
    public function configValidations(): array;
}
