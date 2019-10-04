<?php declare(strict_types = 1);

namespace Business\Domain\Exceptions;

class AdapterException extends \Exception
{
    const ERR_PREFIX_TOKEN = 1000;
    const ERR_PREFIX_HASH = 2000;
}