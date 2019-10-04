<?php declare(strict_types = 1);

namespace Business\Adapters\Hash\Bcrypt;

use Business\Domain\Exceptions\AdapterException;

class BcryptAdapterException extends AdapterException
{
    const ERR_PREFIX = parent::ERR_PREFIX_HASH;

    public static function costNotInt($previous = null)
    {
        throw new BcryptAdapterException('Cost should be an integer', self::ERR_PREFIX + 1, $previous);
    }
}