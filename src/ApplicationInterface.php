<?php

declare(strict_types=1);

namespace Inferno\Application;

interface ApplicationInterface
{
    /**
     * @return void
     */
    public function run(): void;

    /**
     * @param string|callable|\Psr\Http\Server\MiddlewareInterface $middleware
     *
     * @return void
     */
    public function pipe($middleware) : void;

    /**
     * @param callable $routes
     *
     * @return void
     */
    public function route(callable $routes) : void;

    /**
     * @return string
     */
    public function getLocale() : string;

    /**
     * @param string $locale
     *
     * @return void
     */
    public function setLocale(string $locale) : void;
}
