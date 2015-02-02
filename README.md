# Indigo XML-RPC

[![Latest Version](https://img.shields.io/github/release/indigophp/xmlrpc.svg?style=flat-square)](https://github.com/indigophp/xmlrpc/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/indigophp/xmlrpc/develop.svg?style=flat-square)](https://travis-ci.org/indigophp/xmlrpc)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/indigophp/xmlrpc.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/xmlrpc)
[![Quality Score](https://img.shields.io/scrutinizer/g/indigophp/xmlrpc.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/xmlrpc)
[![HHVM Status](https://img.shields.io/hhvm/indigophp/xmlrpc.svg?style=flat-square)](http://hhvm.h4cc.de/package/indigophp/xmlrpc)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/xmlrpc.svg?style=flat-square)](https://packagist.org/packages/indigophp/xmlrpc)

**Extensions for [lstrojny/fxmlrpc](https://github.com/lstrojny/fxmlrpc).**


## Install

Via Composer

``` bash
$ composer require indigophp/xmlrpc
```

## Usage

The package provides custom `Client` implementations using [egeloen/ivory-http-adapter](https://github.com/egeloen/ivory-http-adapter).

``` php
use Indigo\Xmlrpc\Client;
use Ivory\HttpAdapter\FileGetContentsHttpAdapter;

// You can also pass a parser and a serializer implementation as in case of the original fXmlRpc implementation
$client = new Client('http://xmlrpc/RPC2', new FileGetContentsHttpAdapter);
```


## Testing

``` bash
$ phpspec run
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/xmlrpc/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
