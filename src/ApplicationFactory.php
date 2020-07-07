<?php

declare(strict_types=1);

namespace Inferno\Container;

use Inferno\Application\Application;
use Inferno\Application\ApplicationInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ApplicationFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Inferno\Application\ApplicationInterface
     */
    public function __invoke(ContainerInterface $container): ApplicationInterface
    {
        return new Application(
            $container->get(ResponseEmitterInterface::class),
            $container->get(RequestHandlerInterface::class),
            $container->get(RequestFactoryInterface::class),
            $container->get(RequestFactoryInterface::class),
        );
    }
}
