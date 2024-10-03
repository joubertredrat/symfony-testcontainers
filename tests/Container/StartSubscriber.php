<?php

declare(strict_types=1);

namespace App\Tests\Container;

use PHPUnit\Event\TestRunner\ExecutionStarted;
use PHPUnit\Event\TestRunner\ExecutionStartedSubscriber;

class StartSubscriber extends Base implements ExecutionStartedSubscriber
{
    const INTEGRATION_NAME = 'Integration';

    public function notify(ExecutionStarted $event): void
    {
        $start = false;

        foreach ($event->testSuite()->tests()->getIterator() as $test) {
            if (str_contains($test->file(), self::INTEGRATION_NAME)) {
                $start = true;
                break;
            }
        }

        if ($start) {
            self::start();
        }
    }
}
