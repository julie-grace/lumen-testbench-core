<?php

namespace Lumen\Testbench\Tests\Integrations;

use Lumen\Testbench\TestCase;

class CustomConfigurationTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            '\Lumen\Testbench\Tests\Stubs\Providers\CustomConfigServiceProvider',
        ];
    }

    /** @test */
    public function it_can_override_existing_configuration_on_register()
    {
        $this->assertSame('bar', config('database.redis.foo'));
    }
}
