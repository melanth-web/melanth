<?php

namespace Melanth\Tests\Foundation;

use Melanth\Foundation\Application;
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

        $router = $this->createMock(Router::class);
        $router->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($request))
            ->will($this->returnValue($response));

        $this->app->instance('router', $router);

        $actual = $this->kernel->handle($request);
        $this->assertInstanceOf(Response::class, $actual);
    }

    public function testBootstrap()
    {
        $this->app->boot();
        $this->assertNull($this->kernel->bootstrap());
    }
}
