<?php

namespace Lumen\Testbench\Concerns;

use Lumen\Testbench\Database\MigrateProcessor;

trait WithLumenMigrations
{
    /**
     * Migrate Laravel's default migrations.
     *
     * @param  array|string  $database
     *
     * @return void
     */
    protected function loadLumenMigrations($database = []): void
    {
        $options = \is_array($database) ? $database : ['--database' => $database];

        $options['--path'] = 'database/migrations';

        $migrator = new MigrateProcessor($this, $options);
        $migrator->up();

        $this->beforeApplicationDestroyed(static function () use ($migrator) {
            $migrator->rollback();
        });
    }
}
