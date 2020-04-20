# Domain Port Check

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gemz/port.svg?style=flat-square)](https://packagist.org/packages/gemz/port)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/gemzio/port/run-tests?label=tests)](https://github.com/gemzio/port/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Quality Score](https://img.shields.io/scrutinizer/g/gemzio/port.svg?style=flat-square)](https://scrutinizer-ci.com/g/gemzio/port)
[![Total Downloads](https://img.shields.io/packagist/dt/gemz/port.svg?style=flat-square)](https://packagist.org/packages/gemz/port)

Check ports of a domain. This package uses under the hood [ReactPHP](https://github.com/reactphp/reactphp).

## Installation

You can install the package via composer:

```bash
composer require gemz/port
```

## Usage

``` php
use \Gemz\Port\Port;

$ports = new Port('gemz.io');
$ports = Port::for('gemz.io');

// check all default ports on tcp
$checks = Port::for('gemz.io')->check();

// check only specific ports on tcp
$checks = Port::for('gemz.io')->useTcp()->check(80, 8080);

// check only specific ports on udp
$checks = Port::for('gemz.io')->useUdp()->check(110, 140);

// check only specific ports on tls
$checks = Port::for('gemz.io')->useTls()->check(443);

// check with array for specific port <-> protocol checks
// if protocol is requested other protocol settings via useTcp() and so on will be ignored
$checks = Port::for('gemz.io')->check([80 => 'tcp', 2525 => 'udp', 443 => 'tls']);

// check with array for specific port 
$checks = Port::for('gemz.io')->useTcp()->check([80, 2525, 443]);

// get supported protocols
$protocols = Port::for('gemz.io')->getProtocols();

// get domain
$protocols = Port::for('gemz.io')->getDomain();

```

### Testing

``` bash
composer test
composer test-coverage
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email stefan@sriehl.com instead of using the issue tracker.

## Credits

- [Stefan Riehl](https://github.com/stefanriehl)
- [All Contributors](../../contributors)

## Support us

Gemz.io is maintained by [Stefan Riehl](https://github.com/stefanriehl). You'll find all open source
projects on [Gemz.io github](https://github.com/gemzio).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
