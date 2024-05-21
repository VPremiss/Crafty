<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Installable\Interfaces;

use VPremiss\Crafty\Utilities\Installable\Enums\AssetType;

// ? A package-tools service provider's
interface Installable
{
    public function seederFilePaths(): array;

    // * =================================
    // * via HasInstallationCommand trait
    // * ===============================

    public function installationCommand(): void;

    public function copyToWorkbenchSkeleton(AssetType $type): void;

    // * ============================
    // * via HasPackageHelpers trait
    // * ==========================

    public function packageNamespace(): string;

    public function packageShortName(): string;

    public function packagePublishes(array $paths, string $tag): void;
}
