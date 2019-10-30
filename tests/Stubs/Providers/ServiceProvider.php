<?php

namespace Lumen\Testbench\Tests\Stubs\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(realpath(__DIR__.'/../../migrations'));
    }
}
