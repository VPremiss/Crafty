<?php

declare(strict_types=1);

namespace VPremiss\Crafty;

use VPremiss\Crafty\Support\Concerns\HandlesSeeding;
use VPremiss\Crafty\Utilities\Configurated\Support\Concerns\HandlesConfiguration;

class CraftyPackage
{
    use HandlesConfiguration;
    use HandlesSeeding;
}
