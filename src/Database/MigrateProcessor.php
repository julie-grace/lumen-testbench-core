<?php

namespace Lumen\Testbench\Database;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Testing\PendingCommand;
use Lumen\Testbench\Contracts\TestCase;

class MigrateProcessor
{
    /**
     * The testbench instance.
     *
     * @var \Lumen\Testbench\Contracts\TestCase
     */
    protected $testbench;

    /**
     * The migrator options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Construct a new schema migrator.
     *
     * @param \Lumen\Testbench\Contracts\TestCase  $testbench
     * @param array  $options
     */
    public function __construct(TestCase $testbench, array $options = [])
    {
        $this->testbench = $testbench;
        $this->options = $options;
    }

    /**
     * Run migration.
     *
     * @return $this
     */
    public function up()
    {
        $this->dispatch('migrate');

        return $this;
    }

    /**
     * Rollback migration.
     *
     * @return $this
     */
    public function rollback()
    {
        $this->dispatch('migrate:rollback');

        return $this;
    }

    /**
     * Dispatch artisan command.
     *
     * @param  string $command
     *
     * @return void
     */
    protected function dispatch(string $command): void
    {
        $console = $this->testbench->artisan($command, $this->options);

        if ($console instanceof PendingCommand) {
            $console->run();
        }
    }
}
