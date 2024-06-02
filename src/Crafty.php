<?php

declare(strict_types=1);

namespace VPremiss\Crafty;

use VPremiss\Crafty\Support\Concerns;

class Crafty
{
    use Concerns\CraftilyHandlesDatabase;
    use Concerns\CraftilyHandlesFiltering;
    use Concerns\CraftilyHandlesGeneration;
    use Concerns\CraftilyHandlesManipulation;
    use Concerns\CraftilyHandlesValidation;
}
