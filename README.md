# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/eolabs-io/guzzle-mws-middleware.svg?style=flat-square)](https://packagist.org/packages/eolabs-io/guzzle-mws-middleware)
[![Build Status](https://img.shields.io/travis/eolabs-io/guzzle-mws-middleware/master.svg?style=flat-square)](https://travis-ci.org/eolabs-io/guzzle-mws-middleware)
[![Quality Score](https://img.shields.io/scrutinizer/g/eolabs-io/guzzle-mws-middleware.svg?style=flat-square)](https://scrutinizer-ci.com/g/eolabs-io/guzzle-mws-middleware)
[![Total Downloads](https://img.shields.io/packagist/dt/eolabs-io/guzzle-mws-middleware.svg?style=flat-square)](https://packagist.org/packages/eolabs-io/guzzle-mws-middleware)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require eolabs/guzzle-mws-middleware
```

## Usage

``` php
$mwsMiddleware = AmazonMwsMiddleware::withSecretKey('testSecret');

$handlerStack = HandlerStack::create($mock);
$handlerStack->push($mwsMiddleware);

$base_uri = "https://mws.amazonservices.com";
$this->client = new Client(['handler' => $handlerStack, 'base_uri' => $base_uri]);

$this->client->post('/Feeds/2009-01-01', ['form_params' => ['merchantId' => '']]);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email thall@eolabs.io instead of using the issue tracker.

## Credits

- [Tim Hall](https://github.com/eolabs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).