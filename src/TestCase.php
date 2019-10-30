<?php

namespace Lumen\Testbench;

use AlbertCht\Lumen\Testing\Concerns\InteractsWithAuthentication;
use AlbertCht\Lumen\Testing\Concerns\InteractsWithConsole;
use AlbertCht\Lumen\Testing\Concerns\InteractsWithContainer;
use AlbertCht\Lumen\Testing\Concerns\InteractsWithDatabase;
use AlbertCht\Lumen\Testing\Concerns\InteractsWithExceptionHandling;
use AlbertCht\Lumen\Testing\Concerns\MakesHttpRequests;
use AlbertCht\Lumen\Testing\Concerns\MocksApplicationServices;
use PHPUnit\Framework\TestCase as PHPUnit;

abstract class TestCase extends PHPUnit implements Contracts\TestCase
{
    use Concerns\Testing,
        InteractsWithConsole,
        InteractsWithContainer,
        InteractsWithAuthentication,
        InteractsWithDatabase,
        InteractsWithExceptionHandling,
        MakesHttpRequests,
        MocksApplicationServices;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tearDownTheTestEnvironment();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        return $this->setUpTheTestEnvironmentTraits($uses);
    }

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    protected function refreshApplication()
    {
        $this->app = $this->createApplication();
        $this->app->boot();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Define your environment setup.
    }
}
