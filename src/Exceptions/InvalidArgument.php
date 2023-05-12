<?php

namespace Msaid\Port\Exceptions;

class InvalidArgument extends \InvalidArgumentException
{
    public static function domainIsNotValid(string $domain): self
    {
        return new self("The given domain `{$domain}` is not valid");
    }

    public static function portOrProtocolIsNotValid(string $port, string $protocol): self
    {
        return new self("The given port `{$port}` or protocol `{$protocol}` is not valid");
    }
}
