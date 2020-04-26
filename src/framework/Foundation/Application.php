<?php

namespace Melanth\Foundation;

use Melanth\Container\Container;
use Melanth\Contracts\Foundation\Application as ApplicationContract;
use Melanth\Contracts\Http\Request;
use Melanth\Contracts\Http\Response;
use Melanth\Support\ServiceProvider;

class Application extends Container implements ApplicationContract
{
    /**
     * The base path of the application.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The service provider mapping.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Determine whether all of the service providers are bootstrapped.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * Create a new application instance.
     *
     * @param string|null $basePath The base path.
     *
     * @return void
     */
    public function __construct(string $basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBinding();
        $this->registerBaseServiceProviders();
    }

    /**
     * Set the base path to the application.
     *
     * @param string $basePath The base path.
     *
     * @return \Melanth\Foundation\Application
     */
    public function setBasePath(string $basePath) : Application
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    /**
     * Get the absolute path.
     *
     * @param string $path The path append to the base path.
     *
     * @return string
     */
    public function path(string $path = '') : string
    {
        return $this->basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Register the application into the container.
     *
     * @return void
     */
    protected function registerBaseBinding() : void
    {
        $this->setInstance($this);
        $this->instance('app', $this);
        $this->instance(Containr::class, $this);
    }

    /**
     * Register the base service providers into the container.
     *
     * @return void
     */
    protected function registerBaseServiceProviders() : void
    {
        //
    }

    /**
     * Register a service provider.
     *
     * @param string|\Melanth\Support\ServiceProvider $provider The service provider.
     *
     * @return \Melanth\Support\ServiceProvider
     */
    public function register($provider) : ServiceProvider
    {
        if ($registered = $this->getProvider($provider)) {
            return $registered;
        }

        if (is_string($provider)) {
            $provider = new $provider($this);
        }

        $provider->register();

        return $this->providers[get_class($provider)] = $provider;
    }

    /**
     * Get the service provider.
     *
     * @param string|\Melanth\Support\ServiceProvider $provider The service provider.
     *
     * @return \Melanth\Support\ServiceProvider
     */
    public function getProvider($provider) : ?ServiceProvider
    {
        $provider = is_string($provider) ? $provider : get_class($provider);

        return $this->providers[$provider] ?? null;
    }

    /**
     * Bootstrap all of the service providers.
     *
     * @return void
     */
    public function boot() : void
    {
        if ($this->booted) {
            return;
        }

        foreach (array_values($this->providers) as $provider) {
            $provider->boot();
        }

        $this->booted = true;
    }

    /**
     * Determine whether the service providers are bootstrapped
     *
     * @return bool
     */
    public function isBooted() : bool
    {
        return $this->booted;
    }
}
