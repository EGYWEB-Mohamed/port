<?php

namespace Gemz\Port\Tests;

use Gemz\Port\Exceptions\InvalidArgument;
use Gemz\Port\Port;
use PHPUnit\Framework\TestCase;

class PortTest extends TestCase
{
    /** @var string */
    protected $domain = 'gemz.io';

    /** @var Port */
    protected $port;

    public function setUp(): void
    {
        $this->port = new Port($this->domain);
    }

    public function test_throws_exception_if_port_not_valid()
    {
        $this->expectException(InvalidArgument::class);

        $this->port->check('akjshdaksjhdakjshdka');
    }

    public function test_throws_exception_if_protocol_not_valid()
    {
        $this->expectException(InvalidArgument::class);

        $this->port->check([80 => 'abc']);
    }

    public function test_throws_exception_if_port_and_protocol_not_valid()
    {
        $this->expectException(InvalidArgument::class);

        $this->port->check(['987' => 'abc']);
    }

    public function test_can_check_one_port(): void
    {
        $checks = $this->port
            ->setTimeout(0.1)
            ->check(80);

        $this->assertArrayHasKey('port', $checks[0]);
        $this->assertArrayHasKey('protocol', $checks[0]);
        $this->assertArrayHasKey('open', $checks[0]);
    }

    public function test_can_check_multiple_ports_as_integers(): void
    {
        $checks = $this->port
            ->setTimeout(0.1)
            ->check(80, 443);

        $this->assertArrayHasKey('port', $checks[0]);
        $this->assertArrayHasKey('protocol', $checks[0]);
        $this->assertArrayHasKey('open', $checks[0]);
    }

    public function test_can_check_multiple_ports_with_protocols_as_array(): void
    {
        $checks = $this->port
            ->setTimeout(0.1)
            ->check([80 => 'tcp', 443 => 'tls']);

        $this->assertTrue(count($checks) == 2);
        $this->assertArrayHasKey('port', $checks[0]);
        $this->assertArrayHasKey('protocol', $checks[0]);
        $this->assertArrayHasKey('open', $checks[0]);
    }

    public function test_throws_exception_if_domain_is_empty(): void
    {
        $this->expectException(InvalidArgument::class);

        $checks = Port::for('')
            ->setTimeout(0.1)
            ->check([80 => 'tcp', 443 => 'tls']);
    }

    public function test_can_get_domain(): void
    {
        $domain = $this->port->getDomain();

        $this->assertSame($domain, $this->domain);
    }

    public function test_can_get_default_ports(): void
    {
        $ports = $this->port->getDefaultPorts();

        $this->assertArrayHasKey(80, $ports);
    }

    public function test_can_get_protocols(): void
    {
        $protocols = $this->port->getProtocols();

        $this->assertTrue(in_array('tcp', $protocols));
        $this->assertTrue(in_array('ssl', $protocols));
        $this->assertTrue(in_array('tls', $protocols));
        $this->assertTrue(in_array('udp', $protocols));
    }

    public function test_can_set_tcp_protocol_for_all_ports(): void
    {
        $checks = $this->port->useTcp()->check(80, 443);

        $this->assertTrue($checks[0]['protocol'] == 'tcp');
    }

    public function test_can_set_tls_protocol_for_all_ports(): void
    {
        $checks = $this->port->useTls()->check(80, 443);

        $this->assertTrue($checks[0]['protocol'] == 'tls');
    }

    public function test_can_set_ssl_protocol_for_all_ports(): void
    {
        $checks = $this->port->useSsl()->check(80, 443);

        $this->assertTrue($checks[0]['protocol'] == 'ssl');
    }

    public function test_can_set_udp_protocol_for_all_ports(): void
    {
        $checks = $this->port->useUdp()->check(80, 443);

        $this->assertTrue($checks[0]['protocol'] == 'udp');
    }

    public function test_can_override_protocol_for_specific_ports(): void
    {
        $checks = $this->port->useUdp()->check([80 => 'tcp', 443 => 'tls']);

        $this->assertTrue($checks[0]['protocol'] == 'tcp');
    }

}
