<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Installable\Enums;

enum AssetType: string
{
    case Config = 'config';
    case Migration = 'migration';
    case Seeder = 'seeder';
}
