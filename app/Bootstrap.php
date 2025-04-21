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
        $this->initializeEnvironment();

        $this->setupContainer();

        return $this->configurator->createContainer();
    }

    public function initializeEnvironment(): void
    {
        $dotenv = Dotenv::createImmutable($this->rootDir);
        $dotenv->load(); // This should populate $_SERVER and $_ENV with values

        $this->configurator->enableTracy($this->rootDir . '/log');
        $this->configurator->setDebugMode(true);


        $this->configurator->createRobotLoader()
            ->addDirectory(__DIR__. '/Presenter')
            ->register();
    }

    private function setupContainer(): void
    {
        $configDir = $this->rootDir . '/config';
        $this->configurator->addConfig($configDir . '/config.neon');
    }
}
