<?php

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\YamlFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Tools\Console\ConsoleRunner;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// load env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// use attribute metadata (you can also use annotations if you prefer)
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/app/src/Entity'], // change if your entities live elsewhere
    isDevMode: true
);

$connectionParams = [
    'url' => $_ENV['DATABASE_URL'],
    'driver' => 'pdo_sqlite',
];

$connection = DriverManager::getConnection($connectionParams, $config);


$entityManager = new EntityManager($connection, $config);

$migrationsConfig = new YamlFile(__DIR__ . '/doctrine-migrations.yaml');

$dependencyFactory = DependencyFactory::fromEntityManager(
    $migrationsConfig,
    new ExistingEntityManager($entityManager)
);

ConsoleRunner::run([
    new DiffCommand($dependencyFactory),
    new MigrateCommand($dependencyFactory),
    new StatusCommand($dependencyFactory),
    new ExecuteCommand($dependencyFactory),
    new GenerateCommand($dependencyFactory),
    new VersionCommand($dependencyFactory),
]);
