<?php

namespace Lumen\Testbench\Tests;

use Illuminate\Support\Facades\Config;
use Lumen\Testbench\TestCase;

class ConfigTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->config['database.default'] = 'testing';
    }

    /** @test */
    public function it_loads_config_facade()
    {
        $this->assertEquals('testing', Config::get('database.default'));
    }

    /** @test */
    public function it_loads_config_helper()
    {
        $this->assertEquals('testing', $this->app->config->get('database.default'));
    }
}
