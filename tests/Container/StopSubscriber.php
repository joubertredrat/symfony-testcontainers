<?php

declare(strict_types=1);

namespace App\Tests\Container;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber;

class StopSubscriber extends Base implements ExecutionFinishedSubscriber
{
    public function notify(ExecutionFinished $event): void
    {
        self::stop();
    }
}
