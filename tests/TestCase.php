<?php

namespace EoLabs\GuzzleMwsMiddleware\Tests;

use EoLabs\GuzzleMwsMiddleware\AmazonMwsMiddleware;
use EoLabs\GuzzleMwsMiddleware\UrlSigner;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var array */
    protected $historyStack = [];

    public function setUp()
    {
        parent::setUp();
        $this->configure();
    }

    public function configure()
    {
        $mock = new MockHandler([
            new Response(200, [], 'First Response'),
            new Response(200, [], 'Second Response'),
        ]);

        $historyMiddleware = Middleware::history($this->historyStack);
        $mwsMiddleware = AmazonMwsMiddleware::withSecretKey('testSecret');

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($mwsMiddleware);
        $handlerStack->push($historyMiddleware);

        $base_uri = "https://mws.amazonservices.com";
        $this->client = new Client(['handler' => $handlerStack, 'base_uri' => $base_uri]);

        $this->client->post('/Feeds/2009-01-01', ['form_params' => $this->getFormParams()]);
        $this->client->post('/Feeds/2009-01-01', ['form_params' => $this->getFormParams(['Timestamp' => ''])]);
    }

    public function getFormParams(array $params = []) : array
    {
        return array_merge(
                [
                  'Version' => '2009-01-01',
                  'AWSAccessKeyId' => '0PExampleR2',
                  'Action' => 'SubmitFeed',
                  'FeedType' => '_POST_INVENTORY_AVAILABILITY_DATA_',
                  'MWSAuthToken' => 'amzn.mws.4ea38b7b-f563-7709-4bae-87aeaEXAMPLE',
                  'Marketplace' => 'ATExampleER',
                  'SellerId' => 'A1ExampleE6',
                  'SignatureMethod' => 'HmacSHA256',
                  'SignatureVersion' => '2',
                  'Timestamp' => '2009-08-20T01:10:27.607Z',
                ],
                $params
            );
    }

    public function createUrlSigner(string $secret): UrlSigner
    {
        return new UrlSigner($secret);
    }
}
