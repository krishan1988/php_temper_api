<?php declare(strict_types = 1);

namespace Business\Domain\UseCases\Authenticator;

use Business\Domain\Exceptions\DomainException;

class AuthenticatorException extends DomainException
{
    const ERR_PREFIX = parent::ERR_PREFIX_AUTHENTICATOR;

    public static function invalidCredentials($previous = null)
    {
        throw new AuthenticatorException('Invalid Credentials', self::ERR_PREFIX + 1, $previous);
    }

    public static function noUser($previous = null)
    {
        throw new AuthenticatorException('User Does Not Exist', self::ERR_PREFIX + 2, $previous);
    }

    public static function passwordMismatch($previous = null)
    {
        throw new AuthenticatorException('Password Do Not Match', self::ERR_PREFIX + 3, $previous);
    }

    public static function inactive($previous = null)
    {
        throw new AuthenticatorException('Inactive user', self::ERR_PREFIX + 4, $previous);
    }

    public static function alreadyLoggedIn($previous = null)
    {
        throw new AuthenticatorException('User already logged in', self::ERR_PREFIX + 5, $previous);
    }

}