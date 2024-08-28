<?php

declare(strict_types = 1);

namespace App\Tests\Integration;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Testcontainers\Container\MySQLContainer;

class IntegrationTestCase extends KernelTestCase
{
    protected const MYSQL_VERSION = '8.0';
    protected const MYSQL_ROOT_PASSWORD = 'password';
    protected const MYSQL_PORT = '19306';

    protected static ?MySQLContainer $container = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!static::$container) {
            static::$container = MySQLContainer::make(self::MYSQL_VERSION, self::MYSQL_ROOT_PASSWORD);
            static::$container->withPort(self::MYSQL_PORT, '3306');
            static::$container->run();

            $kernel = self::bootKernel();
            $container = $kernel->getContainer();

            $application = new Application($kernel);
            $application->setAutoExit(false);

            $application->run(
                new ArrayInput(['command' => 'doctrine:database:create', '--if-not-exists' => true])
            );

            $entityManager = $container->get('doctrine')->getManager();
            $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
            $schemaTool = new SchemaTool($entityManager);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        if (static::$container instanceof MySQLContainer) {
            static::$container->remove();
        }
    }
}
