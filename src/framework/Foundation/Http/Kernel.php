<?php

namespace Melanth\Foundation\Http;

use Melanth\Contracts\Http\Kernel as KernelContract;
use Melanth\Contracts\Http\Request;
use Melanth\Contracts\Http\Response;
use Melanth\Foundation\Application;

class Kernel implements KernelContract
{
    /**
     * The default service bootstrapers.
     *
     * @var array
     */
    protected $bootstrapers = [
        \Melanth\Foundation\Bootstrap\ConfigurationLoader::class,
        \Melanth\Foundation\Bootstrap\RegisterProviders::class,
        \Melanth\Foundation\Bootstrap\BootProviders::class
    ];

    /**
     * The application instance.
     *
     * @var \Melanth\Container\Container
     */
    protected $app;

    /**
     * Create a new kernel instance.
     *
     * @param \Melanth\Foundation\Application $app The application instance.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Melanth\Contracts\Http\Request $request The request instance.
     *
     * @return \Melanth\Contracts\Http\Response
     */
    public function handle(Request $request) : Response
    {
        return $this->dispatchToRouer($request);
    }

    /**
     * Dispatch request throught router.
     *
     * @param \Melanth\Http\Request $request The request instance.
     *
     * @return \Melanth\Http\Response
     */
    protected function dispatchToRouer(Request $request) : Response
    {
        $this->app->instance('request', $request);

        $this->bootstrap();

        return $this->app['router']->dispatch($request);
    }

    /**
     * Bootstrap all of the bootstrapped services.
     *
     * @return void
     */
    public function bootstrap() : void
    {
        if ($this->app->isBooted()) {
            return;
        }

        foreach ($this->getBootstrapers() as $bootstraper) {
            $this->app->make($bootstraper)->bootstrap($this->app);
        }
    }

    /**
     * Get all of the bootstrapers.
     *
     * @return array
     */
    public function getBootstrapers() : array
    {
        return $this->bootstrapers;
    }
}
