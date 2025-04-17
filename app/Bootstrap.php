<?php

declare(strict_types=1);

use Nette\Bootstrap\Configurator;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$configurator = new Configurator;

// Log dir
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Debug (you can switch to false for prod)
$configurator->setDebugMode(true);
$configurator->enableTracy(__DIR__ . '/../log');

// Load configs
$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

// Create DI container
return $configurator->createContainer();
