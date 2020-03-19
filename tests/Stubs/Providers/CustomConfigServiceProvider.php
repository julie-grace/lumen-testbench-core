<?php

namespace Lumen\Testbench\Tests\Stubs\Providers;

class CustomConfigServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $config = [
            'foo' => 'bar',
        ];

        foreach ($config as $name => $params) {
            config(['database.redis.'.$name => $params]);
        }
    }
}
