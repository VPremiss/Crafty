<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VPremiss\Crafty\CraftyServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            CraftyServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
