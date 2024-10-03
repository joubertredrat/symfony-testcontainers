<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\ErrorHandler;

require_once sprintf('%s/%s', dirname(__DIR__), 'vendor/autoload.php');

if (file_exists(sprintf('%s/%s', dirname(__DIR__), 'config/bootstrap.php'))) {
    require_once sprintf('%s/%s', dirname(__DIR__), 'config/bootstrap.php');
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(sprintf('%s/%s', dirname(__DIR__), '.env.test'));
}

ErrorHandler::register(null, false);
