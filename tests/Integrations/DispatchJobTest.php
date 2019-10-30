<?php

namespace Lumen\Testbench\Tests;

use Lumen\Testbench\TestCase;
use Lumen\Testbench\Tests\Stubs\Jobs\RegisterUser;

class DispatchJobTest extends TestCase
{
    /** @test */
    public function it_can_triggers_expected_jobs()
    {
        $this->expectsJobs(RegisterUser::class);

        dispatch(new RegisterUser());
    }
}
