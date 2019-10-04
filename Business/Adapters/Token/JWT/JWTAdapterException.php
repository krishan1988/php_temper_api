<?php declare(strict_types = 1);

namespace Business\Adapters\Token\JWT;

use Business\Domain\Exceptions\AdapterException;

class JWTAdapterException extends AdapterException
{
    const ERR_PREFIX = parent::ERR_PREFIX_TOKEN;

    public static function noToken($previous = null)
    {
        throw new JWTAdapterException('No token provided', self::ERR_PREFIX + 1, $previous);
    }

    public static function invalidToken($previous = null)
    {
        throw new JWTAdapterException('Invalid Token', self::ERR_PREFIX + 2, $previous);
    }

    public static function invalidLifetimeValue($previous = null)
    {
        throw new JWTAdapterException('Invalid value provided for token lifetime', self::ERR_PREFIX + 3, $previous);
    }

    public static function noConfig($previous = null)
    {
        throw new JWTAdapterException('A configuration value is not provided', self::ERR_PREFIX + 4, $previous);
    }

    public static function noTokenId($previous = null)
    {
        throw new JWTAdapterException('No token id (jti field) in decoded token', self::ERR_PREFIX + 1, $previous);
    }
}