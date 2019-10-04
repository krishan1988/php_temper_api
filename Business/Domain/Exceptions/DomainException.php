<?php declare(strict_types = 1);

namespace Business\Domain\Exceptions;
use PHPUnit\Exception;

class DomainException extends \PHPUnit\Runner\Exception
{
    const ERR_PREFIX_AUTHENTICATOR = 10000;
    const ERR_PREFIX_STATEMENT_GENERATOR = 20000;
    const ERR_PREFIX_TOKEN_Business = 30000;
    const ERR_PREFIX_DRIVER_VALIDATOR = 40000;
    const ERR_PREFIX_SHIFT_STATUS_UPDATER = 50000;
    const ERR_PREFIX_DRIVER_FETCHER = 60000;
    const ERR_PREFIX_INFO_FETCHER = 70000;
    const ERR_PREFIX_SUPPORT_FETCHER = 80000;
    const ERR_PREFIX_DIRECTIONAL_STATUS_UPDATER = 90000;
    const ERR_PREFIX_TRIP_FETCHER = 100000;
}
