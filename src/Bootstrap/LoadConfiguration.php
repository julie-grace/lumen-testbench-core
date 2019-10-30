<?php

namespace Lumen\Testbench\Bootstrap;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Laravel\Lumen\Application;
use Symfony\Component\Finder\Finder;

class LoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Laravel\Lumen\Application  $app
     *
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $items = [];

        $app->instance('config', $config = new Repository($items));

        $this->loadConfigurationFiles($app, $config);

        \mb_internal_encoding('UTF-8');
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Config\Repository  $config
     *
     * @return void
     */
    protected function loadConfigurationFiles(Application $app, RepositoryContract $config): void
    {
        foreach ($this->getConfigurationFiles($app) as $key => $path) {
            $config->set($key, require $path);
        }
    }

    /**
     * Get all of the configuration files for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return array
     */
    protected function getConfigurationFiles(Application $app): array
    {
        $files = [];

        $path = \realpath(__DIR__.'/../../lumen/config');

        foreach (Finder::create()->files()->name('*.php')->in($path) as $file) {
            $files[\basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        return $files;
    }
}
