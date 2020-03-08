<?php

namespace EoLabs\GuzzleMwsMiddleware;

use GuzzleHttp\Psr7;
use Psr\Http\Message\RequestInterface;

class UrlSigner
{
    /** @var string */
    private $secret;

    /** @var Psr\Http\Message\RequestInterface */
    private $request;

    public function __construct(string $secret)
    {
    	$this->setSecret($secret);
    }

    public function handle($request, $options)
    {
    	$signature = $this->setRequest($request)
    					  ->signRequest();

	    $method = $this->getMethod();
	    $uri  = $this->getPath(); 
	    $headers = $this->getHeaders(); 
	    $body = $this->getBody( ['Signature' => $signature] );

	    return new Psr7\Request($method, $uri, $headers, $body);
    }

    protected function signRequest() : string
    {
    	$key = $this->getSecret();
    	$parameters = $this->getParameters();
    	$algorithm = $parameters['SignatureMethod'];
    	$stringToSign = $this->getStringToSign();

    	return $this->sign($stringToSign, $key, $algorithm);
    }

    protected function getStringToSign() : string
    {
    	$method = $this->getMethod();
    	$host = $this->getHost();
    	$path = $this->getPath();
    	$parameters = $this->getSortedParameterString();

    	return "{$method}\n{$host}\n{$path}\n{$parameters}";
    }

	public function getSortedParameterString() : string
	{
		$parameters = $this->getParameters();
		uksort($parameters, 'strcmp');
    	
    	return Psr7\build_query($parameters);
	}

    protected function getParameters() : array
    {
    	$body = $this->getBody();

    	return Psr7\parse_query($body);
    }

    /**
     * Runs the hash, copied from Amazon
     * @param string $data
     * @param string $key
     * @param string $algorithm 'HmacSHA1' or 'HmacSHA256'
     * @return string
     * @throws Exception
     */
    protected function sign($data, $key, $algorithm) : string
    {
    	$hash = 'sha256';
        
        if ($algorithm === 'HmacSHA1')
            $hash = 'sha1';

        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }

    public function setRequest($request)
    {
    	$this->request = $request;

    	return $this;
    }

    public function getRequest() : RequestInterface
    {
    	return $this->request;
    }

    public function setSecret($secret)
    {
    	$this->secret = $secret;

    	return $this;
    }

    public function getSecret() : string
    {
    	return $this->secret;
    }

    public function getHeaders() : array
    {
    	return $this->getRequest()->getHeaders();
    }

    public function getMethod() : string
    {
    	return $this->getRequest()->getMethod();
    }

    public function getHost() : string
    {
    	return $this->getRequest()->getUri()->getHost();
    }

    public function getPath() : string
    {
    	return $this->getRequest()->getUri()->getPath();
    }

    public function getBody($parameters = []) : string
    {
    	$body = (string) $this->getRequest()->getBody();

    	if(count($parameters) > 0)
    		$body .= '&' . http_build_query($parameters);

    	return $body;
    }

}