<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Configurated\Interfaces;

interface Configurated
{
    /**
     * It's must return an array of configuration keys pointing towards their
     * validation method closures with expected values.
     *
     * @return array<string, callable>
     */
    public function configurationValidations(): array;
}
