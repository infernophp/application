<?php

declare(strict_types=1);

namespace Inferno\Application\Dependency;

use Pimple\Container;

/**
 * ATTENTION: If you don´t need a singleton container it is better to use Pimple\Container instead.
 */
class SingletonContainer extends Container
{
    /**
     * @var \Pimple\Container|null
     */
    protected static $container;

    /**
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        if (static::$container === null) {
            static::$container = $this;
        }
    }

    /**
     * @return \Pimple\Container
     */
    public static function getContainer(): Container
    {
        if (static::$container === null) {
           throw new \RuntimeException('Container is not initialized');
        }

        return static::$container;
    }
}
