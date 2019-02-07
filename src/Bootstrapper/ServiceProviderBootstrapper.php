<?php

declare(strict_types=1);

namespace Inferno\Application\Bootstrapper;

use Inferno\Application\ApplicationInterface;
use Pimple\ServiceProviderInterface;

class ServiceProviderBootstrapper implements BootstrapperInterface
{
    /**
     * @var string
     */
    protected $serviceProviderKey;

    /**
     * @param string $serviceProviderKey
     */
    public function __construct(string $serviceProviderKey)
    {
        $this->serviceProviderKey = $serviceProviderKey;
    }

    /**
     * @param \Inferno\Application\ApplicationInterface $application
     *
     * @return void
     */
    public function bootstrap(ApplicationInterface $application): void
    {
        $container = $application->getContainer();
        foreach ($container->offsetGet($this->serviceProviderKey) as $serviceProvider) {
            if (\is_string($serviceProvider) && \class_exists($serviceProvider)) {
                $serviceProvider = new $serviceProvider;
            }

            if ($serviceProvider instanceof ServiceProviderInterface) {
                $container->register($serviceProvider);
            }
        }
    }
}
