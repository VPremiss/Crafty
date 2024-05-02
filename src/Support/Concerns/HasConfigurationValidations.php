<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasConfigurationValidations
{
    protected function validateDatabasingChunksCountConfig(mixed $value): void
    {
        if (intval($value) < 2) {
            throw new ConfiguratedValidatedConfigurationException(
                "Database chunking count should be more than one. What's the point otherwise?!"
            );
        }
    }

    protected function validateInsertionDefaultPropertiesConfig(mixed $value): void
    {
        if (!is_array($value) || !filled($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Database insertion default properties must be a filled array containing the essentials such as created_at, updated_at, etc.'
            );
        }
    }

    protected function validateHashDigitsCountConfig(mixed $value): void
    {
        if (intval($value) < 8) {
            throw new ConfiguratedValidatedConfigurationException(
                "Requested $value digits for general meta hashing generation, which is too low to maintain uniqueness in identifiers. Please use at least 8 digits."
            );
        }
    }

    protected function validateStringHashSeparatorConfig(mixed $value): void
    {
        if (!is_string($value) || empty($value)) {
            throw new ConfiguratedValidatedConfigurationException('String-hash separator must be a filled string.');
        }
    }
}
