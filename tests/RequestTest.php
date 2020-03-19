<?php

namespace Lumen\Testbench\Tests;

use Lumen\Testbench\TestCase;

class RequestTest extends TestCase
{
    /** @test */
    public function it_can_get_request_information()
    {
        $this->app->router->get('hello', function () {
            return 'hello world';
        });

        $this->get('hello?foo=bar');

        $this->assertSame('http://localhost/hello?foo=bar', $this->currentUri);
        $this->assertSame('http://localhost', url());
        $this->assertSame('hello world', $this->response->getContent());
    }
}
