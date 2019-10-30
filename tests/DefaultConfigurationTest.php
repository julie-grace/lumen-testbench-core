<?php

namespace Lumen\Testbench\Tests;

use Lumen\Testbench\TestCase;

class DefaultConfigurationTest extends TestCase
{
    /** @test */
    public function it_populate_expected_cache_defaults()
    {
        $this->assertEquals('array', $this->app['config']['cache.default']);
    }
}
