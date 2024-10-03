<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\Container\StartSubscriber as ContainerStartSubscriber;
use App\Tests\Container\StopSubscriber as ContainerStopSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

class IntegrationTestExtension implements Extension
{
    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters
    ): void {
        $facade->registerSubscriber(new ContainerStartSubscriber());
        $facade->registerSubscriber(new ContainerStopSubscriber());
    }
}
