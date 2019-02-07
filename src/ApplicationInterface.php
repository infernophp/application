<?php

declare(strict_types=1);

namespace Inferno\Application;

use Inferno\Application\Bootstrapper\BootstrapperInterface;
use Pimple\Container;

interface ApplicationInterface
{
    /**
     * @return \Pimple\Container
     */
    public function getContainer(): Container;

    /**
     * @return string
     */
    public function getBaseDir(): string;

    /**
     * @return \Inferno\Application\ApplicationInterface
     */
    public function addDefaultBootstrapper(): ApplicationInterface;

    /**
     * @param \Inferno\Application\Bootstrapper\BootstrapperInterface $bootstrapper
     *
     * @return \Inferno\Application\ApplicationInterface
     */
    public function addBootstrapper(BootstrapperInterface $bootstrapper): ApplicationInterface;

    /**
     * @return \Inferno\Application\ApplicationInterface
     */
    public function boot(): ApplicationInterface;

    /**
     * @param callable $kernel
     *
     * @return \Inferno\Application\ApplicationInterface
     */
    public function run(callable $kernel): ApplicationInterface;

    /**
     * @return \Inferno\Application\ApplicationInterface
     */
    public function terminate(): ApplicationInterface;
}
