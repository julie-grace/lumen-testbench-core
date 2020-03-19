<?php

namespace Lumen\Testbench\Tests;

use Illuminate\Routing\Router;
use Lumen\Testbench\TestCase;

class RouteTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->router->get('hello', ['as' => 'hi', function () {
            return 'hello world';
        }]);

        $app->router->get('goodbye', ['as' => 'bye', function () {
            return 'goodbye world';
        }]);

        $app->router->group(['prefix' => 'boss'], function ($router) {
            $router->get('hello', ['as' => 'boss.hi', function () {
                return 'hello boss';
            }]);

            $router->get('goodbye', ['as' => 'boss.bye', function () {
                return 'goodbye boss';
            }]);
        });
    }

    /** @test */
    public function it_can_resolve_get_routes()
    {
        $crawler = $this->call('GET', 'hello');

        $this->assertEquals('hello world', $crawler->getContent());

        $crawler = $this->call('GET', 'goodbye');

        $this->assertEquals('goodbye world', $crawler->getContent());
    }

    /** @test */
    public function it_can_resolve_get_routes_with_prefixes()
    {
        $crawler = $this->call('GET', 'boss/hello');

        $this->assertEquals('hello boss', $crawler->getContent());

        $crawler = $this->call('GET', 'boss/goodbye');

        $this->assertEquals('goodbye boss', $crawler->getContent());
    }

    /** @test */
    public function it_can_resolve_name_routes()
    {
        $this->app->router->get('byebye', ['as' => 'bae', function () {
            return route('bye');
        }]);

        $response = $this->call('GET', route('bae'));

        $response->assertStatus(200);
        $this->assertEquals('http://localhost/goodbye', $response->getContent());
    }
}
