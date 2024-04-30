<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Configurated\Traits;

use VPremiss\Crafty\Utilities\Configurated\Support\Events\ConfigurationBroadcastedEvent;

trait HasDispatchedConfigurations
{
    public function packageConfigurations()
    {
        $configurations = [];

        foreach ($this->package->configFileNames as $configFileName) {
            $configurations[$configFileName] = $this->package->basePath("/../config/{$configFileName}.php");
        }

        return $configurations;
    }

    protected function broadcastConfiguration()
    {
        ConfigurationBroadcastedEvent::dispatch(
            $this->packageConfigurations(),
            $this->configValidations(),
        );
    }
}
