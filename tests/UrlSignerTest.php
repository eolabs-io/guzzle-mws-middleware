<?php

namespace EoLabs\GuzzleMwsMiddleware\Tests;

use GuzzleHttp\Psr7;
use Psr\Http\Message\RequestInterface;


class UrlSignerTest extends TestCase
{
    /** @var string */
    protected $expectedSignature = 'OupQxSjIAJfjtUy9uBU4HBLIgvtkO3MeNgmfirAc13A=';


    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_correctly_sign_the_request()
    {
		$actualSignature = $this->getRequestSignature($this->historyStack[0]['request']); 

		$this->assertEquals($this->expectedSignature, $actualSignature);
    }

    /** @test */
    public function it_fails_to_sign_the_request()
    {
		$actualSignature = $this->getRequestSignature($this->historyStack[1]['request']); 

		$this->assertNotEquals($this->expectedSignature, $actualSignature);
    }


    public function getRequestSignature(RequestInterface $request)
    {
    	$body = (string) $request->getBody();
		$params = Psr7\parse_query($body);

		return $params['Signature'];
    }
}