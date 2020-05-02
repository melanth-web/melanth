<?php

namespace Melanth\Tests\Foundation;

use Melanth\Foundation\Application;
use Melanth\Foundation\Bootstrap\ConfigurationLoader;
use Melanth\Foundation\Bootstrap\RegisterProviders;
use Melanth\Foundation\Bootstrap\BootProviders;
use Melanth\Foundation\Http\Kernel;
use Melanth\Http\Request;
use Melanth\Http\Response;
use Melanth\Routing\Router;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    private $app;
    private $kernel;

    public function setUp() : void
    {
        parent::setUp();

        $this->app = new Application;
        $this->kernel = new Kernel($this->app);

        $this->app->instance(ConfigurationLoader::class, $this->createMock(ConfigurationLoader::class));
        $this->app->instance(RegisterProviders::class, $this->createMock(RegisterProviders::class));
        $this->app->instance(BootProviders::class, $this->createMock(BootProviders::class));
        $this->app->instance('router', $this->createMock(Router::class));
    }

    public function tearDown() : void
    {
        $this->app = null;
        $this->kernel = null;

        parent::tearDown();
    }

    public function testHandleHttpRequest()
    {
        $request = Request::create('/foo');
        $response = new Response;

        $this->app['router']->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($request))
            ->will($this->returnValue($response));

        $this->assertSame($response, $this->kernel->handle($request));
    }

    public function testBootstrap()
    {
        $this->app->boot();
        $this->assertNull($this->kernel->bootstrap());
    }
}
