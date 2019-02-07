<?php

declare(strict_types=1);

namespace Inferno\Application;

use Inferno\Application\Bootstrapper\BootstrapperInterface;
use Inferno\Application\Bootstrapper\ConfigBootstrapper;
use Inferno\Application\Bootstrapper\ServiceProviderBootstrapper;
use Pimple\Container;

class Application implements ApplicationInterface
{
    /**
     * @var \Inferno\Application\Bootstrapper\BootstrapperInterface[]
     */
    protected $bootstrappers = [];

    /**
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @param \Pimple\Container|null $container
     * @param string $baseDir
     */
    public function __construct(string $baseDir, ?Container $container = null)
    {
        $this->baseDir = $baseDir;
        $this->container = $container ?? new Container();
    }

    /**
     * @return \Pimple\Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getBaseDir(): string
    {
        return $this->baseDir;
    }

    /**
     * @return \Inferno\Application\ApplicationInterface
     */
    public function addDefaultBootstrapper(): ApplicationInterface
    {
        $this->addBootstrapper(new ConfigBootstrapper($this->baseDir . '/config'));
        $this->addBootstrapper(new ServiceProviderBootstrapper('config.providers'));
        $this->addBootstrapper(new ServiceProviderBootstrapper('app.providers'));

        return $this;
    }

    /**
     * @param \Inferno\Application\Bootstrapper\BootstrapperInterface $bootstrapper
     *
     * @return \Inferno\Application\ApplicationInterface
     */
    public function addBootstrapper(BootstrapperInterface $bootstrapper): ApplicationInterface
    {
        $this->bootstrappers[] = $bootstrapper;

        return $this;
    }

    /**
     * @return \Inferno\Application\ApplicationInterface
     */
    public function boot(): ApplicationInterface
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $bootstrapper->bootstrap($this);
        }

        return $this;
    }

    /**
     * @param callable $kernel
     *
     * @return \Inferno\Application\ApplicationInterface
     */
    public function run(callable $kernel): ApplicationInterface
    {
        $kernel();

        return $this;
    }

    /**
     * @return \Inferno\Application\ApplicationInterface
     */
    public function terminate(): ApplicationInterface
    {
        return $this;
    }
}
