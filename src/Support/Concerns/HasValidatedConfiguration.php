<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasValidatedConfiguration
{
    protected function validateDatabasingChunksCountConfig(): void
    {
        $chunksCount = CraftyPackage::config('crafty.databasing_chunks_count', $this);

        if (intval($chunksCount) < 2) {
            throw new ConfiguratedValidatedConfigurationException(
                "Database chunking count should be more than one. What's the point otherwise?!"
            );
        }
    }

    protected function validateInsertionDefaultPropertiesConfig(): void
    {
        $insertionDefaultProperties = CraftyPackage::config('crafty.insertion_default_properties', $this);

        if (!is_array($insertionDefaultProperties) || !filled($insertionDefaultProperties)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Database insertion default properties must be a filled array containing the essentials such as created_at, updated_at, etc.'
            );
        }
    }

    protected function validateHashDigitsCountConfig(): void
    {
        $digits = CraftyPackage::config('crafty.hash_digits_count', $this);

        if (intval($digits) < 8) {
            throw new ConfiguratedValidatedConfigurationException(
                "Requested $digits digits for general meta hashing generation, which is too low to maintain uniqueness in identifiers. Please use at least 8 digits."
            );
        }
    }

    protected function validateStringHashSeparatorConfig(): void
    {
        $separator = CraftyPackage::config('crafty.string_hash_separator', $this);

        if (!is_string($separator) || empty($separator)) {
            throw new ConfiguratedValidatedConfigurationException('String-hash separator must be a filled string.');
        }
    }
}
