<?php declare(strict_types = 1);

namespace Business\Domain\Exceptions;
use PHPUnit\Exception;

class DomainException extends \PHPUnit\Runner\Exception
{
    const ERR_PREFIX_AUTHENTICATOR = 10000;
    const ERR_PREFIX_STATEMENT_GENERATOR = 20000;
    const ERR_PREFIX_TOKEN_Business = 30000;
}
