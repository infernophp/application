<?php

declare(strict_types=1);

namespace Inferno\Application\Bootstrapper;

use Inferno\Application\ApplicationInterface;
use Pimple\Psr11\Container;

class PsrContainerBootstrapper implements BootstrapperInterface
{
    /**
     * @param \Inferno\Application\ApplicationInterface $application
     *
     * @return void
     */
    public function bootstrap(ApplicationInterface $application): void
    {
        $application->getContainer()->offsetSet(Container::class, new Container($application->getContainer()));
    }
}
