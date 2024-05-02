<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Configurated\Support\Concerns;

use Illuminate\Support\Arr;

trait HandlesConfiguration
{
    protected array $configurations = [];
    protected array $configurationValidations = [];

    public function getConfiguration(string $key, $default = null): mixed
    {
        $value = Arr::get($this->configurations, $key, $default);

        if ($closure = Arr::get($this->configurationValidations, $key)) {
            $closure($value);
        }

        return $value;
    }

    public function setConfiguration(string $key, mixed $value): void
    {
        if (isset($this->configurationValidations[$key])) {
            $this->configurationValidations[$key]($value);
        }

        Arr::set($this->configurations, $key, $value);
    }

    public function setConfigurationValidation(string $key, callable $closure): void
    {
        Arr::set($this->configurationValidations, $key, $closure);
    }
}
