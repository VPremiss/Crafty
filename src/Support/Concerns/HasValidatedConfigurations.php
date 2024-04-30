<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasValidatedConfigurations
{
    protected function validateDatabasingChunksCountConfig(): void
    {
        if (intval(config('crafty.databasing_chunks_count')) < 2) {
            throw new ConfiguratedValidatedConfigurationException(
                "Database chunking count should be more than one. What's the point otherwise?!"
            );
        }
    }

    protected function validateInsertionDefaultPropertiesConfig(): void
    {
        $insertionDefaultProperties = config('crafty.insertion_default_properties');

        if (!is_array($insertionDefaultProperties) || !filled($insertionDefaultProperties)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Database insertion default properties must be a filled array containing the essentials such as created_at, updated_at, etc.'
            );
        }
    }

    protected function validateHashDigitsCountConfig(): void
    {
        if (intval($digits = $digits = config('crafty.hash_digits_count')) < 8) {
            throw new ConfiguratedValidatedConfigurationException(
                "Requested $digits digits for general meta hashing generation, which is too low to maintain uniqueness in identifiers. Please use at least 8 digits."
            );
        }
    }

    protected function validateStringHashSeparatorConfig(): void
    {
        if (!is_string($separator = config('crafty.string_hash_separator')) || empty($separator)) {
            throw new ConfiguratedValidatedConfigurationException('String-hash separator must be a filled string.');
        }
    }
}
