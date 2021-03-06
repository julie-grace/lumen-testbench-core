<?php

namespace Lumen\Testbench\Tests;

use PHPUnit\Framework\TestCase;

class TestCaseTest extends TestCase
{
    /** @test */
    public function it_can_create_the_testcase()
    {
        $testbench = new class() extends \Lumen\Testbench\TestCase {
            //
        };

        $app = $testbench->createApplication();

        $this->assertInstanceOf('\Lumen\Testbench\Contracts\TestCase', $testbench);
        $this->assertInstanceOf('\Laravel\Lumen\Application', $app);
        $this->assertEquals('UTC', date_default_timezone_get());
        $this->assertEquals('testing', $app['env']);
        $this->assertInstanceOf('\Illuminate\Config\Repository', $app['config']);
    }
}
