<?php

declare(strict_types=1);

namespace Inferno\Application\Bootstrapper;

use Inferno\Application\ApplicationInterface;
use Pimple\Container;

class ConfigBootstrapper implements BootstrapperInterface
{
    /**
     * @var string[]
     */
    protected $placeholders;

    /**
     * @var string
     */
    protected $configDirectoryPath;

    /**
     * @param string $configDirectoryPath
     */
    public function __construct(string $configDirectoryPath)
    {
        $this->configDirectoryPath = $configDirectoryPath;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addPlaceholder(string $key, $value): void
    {
        $this->placeholders[$key] = $value;
    }

    /**
     * @param \Inferno\Application\ApplicationInterface $application
     *
     * @return void
     */
    public function bootstrap(ApplicationInterface $application): void
    {
        $container = $application->getContainer();
        $baseDir = $application->getBaseDir();

        $this->addPlaceholder('%baseDir%', $baseDir);
        $container->offsetSet('baseDir', $baseDir);

        $configs = $this->getConfigurationValues($this->configDirectoryPath);
        $configs = $this->replace($configs);

        $this->addConfigurationToContainer($configs, $container);
    }

    /**
     * @param string[][] $configs
     *
     * @param \Pimple\Container $container
     */
    protected function addConfigurationToContainer(array $configs, Container $container): void {
        foreach ($configs as $filename => $config) {
            foreach ($config as $name => $value) {
                $container->offsetSet($filename . '.' . $name, $value);
            }
        }
    }

    /**
     * @param string $configDirectoryPath
     *
     * @return string[]
     */
    protected function getConfigurationValues(string $configDirectoryPath): array
    {
        $configs = [];
        foreach (glob($configDirectoryPath . '/*.php') as $filePath) {
            if (! is_file($filePath)) {
                continue;
            }

            $values = require $filePath;
            if (! \is_array($values)) {
                continue;
            }

            $configs[basename($filePath, '.php')] = $values;
        }

        return $configs;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function replace($value)
    {
        if (\is_string($value)) {
            return strtr($value, $this->placeholders);
        }

        if (\is_array($value)) {
            $value = array_map(function ($value) {
                return $this->replace($value);
            }, $value);
        }

        return $value;
    }
}
