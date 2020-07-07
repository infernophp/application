<?php

declare(strict_types=1);

namespace Inferno\Application;

use Inferno\HttpRequestHandler\Pipeline\PipelineInterface;
use Inferno\HttpRequestHandler\Resolver\ResolverInterface;
use Inferno\Routing\Route\RouteCollectorInterface;
use Throwable;

final class Application implements ApplicationInterface
{
    /**
     * @var \Inferno\HttpRequestHandler\Pipeline\PipelineInterface
     */
    private PipelineInterface $pipeline;

    /**
     * @var \Inferno\HttpRequestHandler\Resolver\ResolverInterface
     */
    private ResolverInterface $resolver;

    /**
     * @var \Inferno\Routing\Route\RouteCollectorInterface
     */
    private RouteCollectorInterface $collector;

    /**
     * @var callable
     */
    private $emitter;

    /**
     * @var callable
     */
    private $requestFactory;

    /**
     * @var callable
     */
    private $errorResponseGenerator;

    /**
     * @var string
     */
    private string $locale;

    /**
     * @param \Inferno\HttpRequestHandler\Pipeline\PipelineInterface $pipeline
     * @param \Inferno\HttpRequestHandler\Resolver\ResolverInterface $resolver
     * @param \Inferno\Routing\Route\RouteCollectorInterface $collector
     * @param callable $emitter
     * @param callable $requestFactory
     * @param callable $errorResponseGenerator
     * @param string $locale - default: en_EN
     */
    public function __construct(
        PipelineInterface $pipeline,
        ResolverInterface $resolver,
        RouteCollectorInterface $collector,
        callable $emitter,
        callable $requestFactory,
        callable $errorResponseGenerator,
        string $locale = 'en_EN'
    ) {
        $this->pipeline = $pipeline;
        $this->resolver = $resolver;
        $this->collector = $collector;
        $this->emitter = $emitter;
        $this->requestFactory = $requestFactory;
        $this->errorResponseGenerator = $errorResponseGenerator;
        $this->locale = $locale;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        try {
            $request = ($this->requestFactory)();
            $response = $this->pipeline->handle($request);
        } catch (Throwable $throwable) {
            $response = ($this->errorResponseGenerator)($throwable);
        }

        ($this->emitter)($response);
    }

    /**
     * @param string|callable|\Psr\Http\Server\MiddlewareInterface $middleware
     *
     * @return void
     */
    public function pipe($middleware) : void
    {
        $middleware = $this->resolver->resolve($middleware);

        $this->pipeline->pipe($middleware);
    }

    /**
     * @param callable $routes
     *
     * @return void
     */
    public function route(callable $routes) : void
    {
        ($routes)($this->collector);
    }

    /**
     * @return string
     */
    public function getLocale() : string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return void
     */
    public function setLocale(string $locale) : void
    {
        $this->locale = $locale;
    }
}
