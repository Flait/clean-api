<?php

declare(strict_types=1);

namespace App;

use Dotenv\Dotenv;
use Nette;
use Nette\Bootstrap\Configurator;

class Bootstrap
{
    private Configurator $configurator;
    private string $rootDir;

    public function __construct()
    {
        $this->rootDir = dirname(__DIR__);
        $this->configurator = new Configurator();
        $this->configurator->setTempDirectory($this->rootDir . '/temp');
    }

    public function bootWebApplication(): Nette\DI\Container
    {
        // Initialize environment (load .env file)
        $this->initializeEnvironment();

        // Set up the container (add all necessary configurations)
        $this->setupContainer();

        // Now create the container, which will have access to all the configurations
        $configurator = $this->configurator->createContainer();

        // Dump the parameters after container is fully set up
        var_dump($configurator->getParameters());
        die;
    }

    public function initializeEnvironment(): void
    {
        // Load the .env file using vlucas/phpdotenv
        $dotenv = Dotenv::createImmutable($this->rootDir);
        $dotenv->load(); // This should populate $_SERVER and $_ENV with values

        // Optional: Log values if needed for debugging
        dump($_SERVER['JWT_SECRET']);  // Check if JWT_SECRET is available
        dump($_SERVER['DATABASE_URL']);  // Check if DATABASE_URL is available

        // Enable Tracy for debugging
        $this->configurator->enableTracy($this->rootDir . '/log');

        // Register the autoloader
        $this->configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();
    }

    private function setupContainer(): void
    {
        $configDir = $this->rootDir . '/config';
        $this->configurator->addConfig($configDir . '/config.local.neon');
        $this->configurator->addConfig($configDir . '/config.neon');
        $this->configurator->addConfig($configDir . '/services.neon');
    }
}
