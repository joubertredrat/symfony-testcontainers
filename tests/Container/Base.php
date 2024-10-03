<?php

declare(strict_types=1);

namespace App\Tests\Container;

use Testcontainers\Container\MySQLContainer;

class Base
{
    protected const MYSQL_VERSION = '8.0';
    protected const MYSQL_ROOT_PASSWORD = 'password';
    protected const MYSQL_PORT = '19306';

    protected static ?MySQLContainer $container = null;

    protected static function start(): void
    {
        if (!static::$container instanceof MySQLContainer) {
            static::$container = MySQLContainer::make(self::MYSQL_VERSION, self::MYSQL_ROOT_PASSWORD);
            static::$container->withPort(self::MYSQL_PORT, '3306');
            static::$container->run();
        }
    }

    protected static function stop(): void
    {
        if (static::$container instanceof MySQLContainer) {
            static::$container->remove();
        }
    }
}
