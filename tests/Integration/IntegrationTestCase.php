<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;

class IntegrationTestCase extends KernelTestCase
{
    protected static ?bool $initiated = false;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!static::$initiated) {
            $kernel = self::bootKernel();
            $container = $kernel->getContainer();

            $application = new Application($kernel);
            $application->setAutoExit(false);

            print PHP_EOL;
            $application->run(
                new ArrayInput(['command' => 'doctrine:database:create','--if-not-exists' => true])
            );

            $entityManager = $container->get('doctrine')->getManager();
            $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
            $schemaTool = new SchemaTool($entityManager);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);

            static::$initiated = true;
        }
    }
}
