<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Adapters;

use DateTime;

interface DateTimeAdapter
{
    public function __construct(array $config);

    /**
     * Get a configured DateTime instance.
     *
     * @param string $time
     * @return DateTime
     */
    public function getInstance(string $time = 'now') : DateTime;
}