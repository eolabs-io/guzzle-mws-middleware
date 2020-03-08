<?php

namespace EoLabs\GuzzleMwsMiddleware\Tests;

use EoLabs\GuzzleMwsMiddleware\AmazonMwsMiddleware;


class AmazonMwsMiddlewareTest extends TestCase
{
    /** @test */
    public function it_has_named_constructors_to_create_instances()
    {
        $this->assertInstanceOf(
            AmazonMwsMiddleware::class,
            AmazonMwsMiddleware::withSecretKey('testKey')
        );
    }
}