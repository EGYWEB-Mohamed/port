# Domain Port Check

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gemz/port.svg?style=flat-square)](https://packagist.org/packages/gemz/port)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/gemzio/port/run-tests?label=tests)](https://github.com/gemzio/port/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Quality Score](https://img.shields.io/scrutinizer/g/gemzio/port.svg?style=flat-square)](https://scrutinizer-ci.com/g/gemzio/port)
[![Total Downloads](https://img.shields.io/packagist/dt/gemz/port.svg?style=flat-square)](https://packagist.org/packages/gemz/port)

Check ports with protocol. This package uses under the hood [ReactPHP Promises](https://github.com/reactphp/promise).

## Installation

You can install the package via composer:

```bash
composer require gemz/port
```

## Usage

``` php
use \Gemz\Port\Port;

$checks = new Port('gemz.io');
// or
$checks = Port::for('gemz.io');

// check all default ports on tcp
$checks = Port::for('gemz.io')->check();

// check specific ports on tcp
$checks = Port::for('gemz.io')->check(80, 8080, 443, 22, 3306, 9000, 9001);

// check only specific ports on tcp
$checks = Port::for('gemz.io')->useTcp()->check(80, 8080);

// check only specific ports on udp
$checks = Port::for('gemz.io')->useUdp()->check(110, 140);

// check only specific ports on tls
$checks = Port::for('gemz.io')->useTls()->check(443);

// check only specific ports on ssl
$checks = Port::for('gemz.io')->useSsl()->check(443);

// check with array for specific port => protocol checks
// if global setting will be ignored
$checks = Port::for('gemz.io')->useTcp()->check([80 => 'tcp', 2525 => 'udp', 443 => 'tls']);

// check with array for specific port 
$checks = Port::for('gemz.io')->useTcp()->check([80, 2525, 443]);

// set timeout, default is 0.25s
$checks = Port::for('gemz.io')->setTimeout(0.4)->checks(80);

// get supported protocols
$protocols = Port::for('gemz.io')->getProtocols();

// get default ports
$ports = Port::for('gemz.io')->getDefaultPorts();

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
