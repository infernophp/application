<?php

declare(strict_types=1);

namespace Inferno\Application\Bootstrapper;

use Inferno\Application\ApplicationInterface;

interface BootstrapperInterface
{
    /**
     * @param \Inferno\Application\ApplicationInterface $application
     *
     * @return void
     */
    public function bootstrap(ApplicationInterface $application): void;
}
