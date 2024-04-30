<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Configurated\Support\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConfigurationBroadcastedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public array $configurations,
        public array $configurationValidations,
    ) {
    }
}
