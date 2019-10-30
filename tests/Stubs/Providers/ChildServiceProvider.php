<?php

namespace Lumen\Testbench\Tests\Stubs\Providers;

class ChildServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['child.loaded'] = true;
    }
}
