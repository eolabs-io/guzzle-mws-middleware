<?php

namespace EoLabs\GuzzleMwsMiddleware;

use EoLabs\GuzzleMwsMiddleware\UrlSigner;
use Psr\Http\Message\RequestInterface;

class AmazonMwsMiddleware
{
    /** @var EoLabs\GuzzleMwsMiddleware\UrlSigner */
    protected $urlSigner;

    private function __construct(UrlSigner $urlSigner)
    {
        $this->urlSigner = $urlSigner;
    }

    public static function withSecretKey(string $secret): AmazonMwsMiddleware
    {
        $urlSigner = new UrlSigner($secret);

        return new static($urlSigner);
    }

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
        	$request = $this->urlSigner->handle($request, $options);
        	
        	return $handler($request, $options);
        };
	}

}
