<?php

namespace Lumen\Testbench\Concerns;

use Laravel\Lumen\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

trait CreatesApplication
{
    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return $app['config']['app.timezone'];
    }

    /**
     * Override application bindings.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function overrideApplicationBindings($app)
    {
        return [];
    }

    /**
     * Resolve application bindings.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    final protected function resolveApplicationBindings($app): void
    {
        foreach ($this->overrideApplicationBindings($app) as $original => $replacement) {
            $app->bind($original, $replacement);
        }
    }

    /**
     * Get application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getApplicationAliases(Application $app)
    {
        return $app['config']['app.aliases'];
    }

    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function overrideApplicationAliases($app)
    {
        return [];
    }

    /**
     * Resolve application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    final protected function resolveApplicationAliases($app): array
    {
        $aliases = new Collection($this->getApplicationAliases($app));
        $overrides = $this->overrideApplicationAliases($app);

        if (! empty($overrides)) {
            $aliases->transform(static function ($alias, $name) use ($overrides) {
                return $overrides[$name] ?? $alias;
            });
        }

        return $aliases->merge($this->getPackageAliases($app))->all();
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [];
    }

    /**
     * Get package bootstrapper.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageBootstrappers($app)
    {
        return [];
    }

    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getApplicationProviders($app)
    {
        return $app['config']['app.providers'];
    }

    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function overrideApplicationProviders($app)
    {
        return [];
    }

    /**
     * Resolve application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    final protected function resolveApplicationProviders($app): array
    {
        $providers = new Collection($this->getApplicationProviders($app));
        $overrides = $this->overrideApplicationProviders($app);

        if (! empty($overrides)) {
            $providers->transform(static function ($provider) use ($overrides) {
                return $overrides[$provider] ?? $provider;
            });
        }

        return $providers->merge($this->getPackageProviders($app))->all();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [];
    }

    /**
     * Get base path.
     *
     * This is where the super minimal `lumen` folder in the root is
     *
     * @return string
     */
    protected function getBasePath()
    {
        return realpath(__DIR__.'/../../lumen');
    }

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = $this->resolveApplication();

        $this->resolveApplicationBindings($app);
        $this->resolveApplicationExceptionHandler($app);
        $this->resolveApplicationCore($app);
        $this->resolveApplicationConfiguration($app);
        $this->resolveApplicationHttpKernel($app);
        $this->resolveApplicationConsoleKernel($app);
        $this->resolveApplicationBootstrappers($app);

        return $app;
    }

    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        return \tap(new Application($this->getBasePath()), static function ($app) {
            $app->bind(
                'Illuminate\Foundation\Bootstrap\LoadConfiguration',
                'Lumen\Testbench\Bootstrap\LoadConfiguration'
            );
        });
    }

    /**
     * Resolve application core configuration implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationConfiguration($app)
    {
        $app->make('Illuminate\Foundation\Bootstrap\LoadConfiguration')->bootstrap($app);

        \tap($this->getApplicationTimezone($app), static function ($timezone) {
            ! \is_null($timezone) && \date_default_timezone_set($timezone);
        });

        $app['config']['app.aliases'] = $this->resolveApplicationAliases($app);
        $app['config']['app.providers'] = $this->resolveApplicationProviders($app);
    }

    /**
     * Resolve application core implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationCore($app)
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);

        return $app->environment();
    }

    /**
     * Resolve application Console Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Console\Kernel', 'Lumen\Testbench\Console\Kernel');
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'Lumen\Testbench\Http\Kernel');
    }

    /**
     * Resolve application HTTP exception handler.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationExceptionHandler($app)
    {
        $app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', 'Lumen\Testbench\Exceptions\Handler');
    }

    /**
     * Resolve application bootstrapper.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationBootstrappers($app)
    {
        $app->make('Lumen\Testbench\Bootstrap\HandleExceptions')->bootstrap($app);

        if ($this instanceof TestCase) {
            Collection::make($this->getAnnotations())->each(function ($location) use ($app) {
                Collection::make($location['environment-setup'] ?? [])
                    ->filter(function ($method) {
                        return ! \is_null($method) && \method_exists($this, $method);
                    })->each(function ($method) use ($app) {
                        $this->{$method}($app);
                    });
            });
        }

        $this->getEnvironmentSetUp($app);

        foreach ($this->resolveApplicationProviders($app) as $provider) {
            $app->register($provider);
        }

        foreach ($this->getPackageBootstrappers($app) as $bootstrap) {
            $app->make($bootstrap)->bootstrap($app);
        }

        $app->make('Laravel\Lumen\Console\Kernel');
    }

    /**
     * Reset artisan commands for the application.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    final protected function resetApplicationArtisanCommands($app)
    {
        $app['Illuminate\Contracts\Console\Kernel']->setArtisan(null);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    abstract protected function getEnvironmentSetUp($app);
}
