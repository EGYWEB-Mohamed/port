<?php

namespace Gemz\Port;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Socket\Connector;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use Gemz\Port\Exceptions\InvalidArgument;

class Port
{
    const PROTOCOL_TCP = 'tcp';
    const PROTOCOL_UDP = 'udp';
    const PROTOCOL_SSL = 'ssl';
    const PROTOCOL_TLS = 'tls';

    /** @var string */
    protected $domain;

    /** @var string */
    protected $protocol = self::PROTOCOL_TCP;

    /** @var float */
    protected $timeout = 0.25;

    /** @var array */
    protected $protocols = ['tcp', 'tls', 'udp', 'ssl'];

    /**
     * @var array
     */
    protected $defaultPorts = [
        22 => 'tcp',
        80 => 'tcp',
        443 => 'tls',
        8080 => 'tcp',
        3306 => 'tcp',
    ];

    /** @var array */
    protected $result = [];

    /** @var array */
    protected $portsWithProtocol = [];

    public static function for(string $domain): self
    {
        return new self($domain);
    }

    public function __construct(string $domain)
    {
        $this->domain = $this->sanitizeDomain($domain);
    }

    protected function sanitizeDomain(string $domain): string
    {
        if (empty($domain)) {
            throw InvalidArgument::domainIsNotValid($domain);
        }

        return str_replace(['http://', 'https://'], '', $domain);
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getProtocols(): array
    {
        return $this->protocols;
    }

    protected function addResult(int $port, string $protocol, bool $isOpen): void
    {
        array_push($this->result, [
           'port' => $port,
           'protocol' => $protocol,
           'open' => $isOpen
        ]);
    }

    /**
     * @param array|int ...$ports
     *
     * @return array
     */
    public function check(...$ports): array
    {
        $this->resolvePorts($ports);

        foreach ($this->portsWithProtocol as $port => $protocol) {
            $this->checkPort($port, $protocol)
                ->then(
                    function () use ($port, $protocol) {
                        $this->addResult($port, $protocol, true);
                    },
                    function () use ($port, $protocol) {
                        $this->addResult($port, $protocol, false);
                    }
                );
        }

        return $this->result;
    }

    protected function getUri(string $protocol): string
    {
        return "{$protocol}://{$this->domain}";
    }

    protected function checkPort(int $port, string $protocol): PromiseInterface
    {
        $deferred = new Deferred();

        $handler = @fsockopen(
            $this->getUri($protocol), $port,$errno,$errstr, $this->timeout
        );

        if (is_resource($handler)) {
            $deferred->resolve();
            fclose($handler);
        } else {
            $deferred->reject();
        }

        return $deferred->promise();
    }

    public function useTcp(): self
    {
        $this->setProtocol(self::PROTOCOL_TCP);

        return $this;
    }

    public function useTls(): self
    {
        $this->setProtocol(self::PROTOCOL_TLS);

        return $this;
    }

    public function useUdp(): self
    {
        $this->setProtocol(self::PROTOCOL_UDP);

        return $this;
    }

    public function useSsl(): self
    {
        $this->setProtocol(self::PROTOCOL_SSL);

        return $this;
    }

    public function setTimeout(float $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }

    protected function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    protected function addPortWithProtocol(int $port, string $protocol): void
    {
        $this->portsWithProtocol[$port] = $protocol;
    }

    protected function resolvePorts(array $ports = []): void
    {
        if (empty($ports)) {
            $ports = $this->defaultPorts;
        }

        $ports = is_array($ports[0] ?? null)
            ? $ports[0]
            : $ports;

        foreach ($ports as $port => $protocol) {

            if (in_array($protocol, $this->protocols) && is_int($port)) {
                $this->addPortWithProtocol($port, $protocol);
                continue;
            }

            if (is_int($protocol)) {
                $this->addPortWithProtocol($protocol, $this->protocol);
                continue;
            }

            throw InvalidArgument::portOrProtocolIsNotValid($port, $protocol);
        }
    }
}
