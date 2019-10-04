<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Adapters;

interface HashAdapter
{
    /**
     * HashAdapter constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = []);

    /**
     * Hash a string.
     *
     * @param string $string
     * @return string
     */
    public function hash(string $string) : string;

    /**
     * Verify the password
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verify(string $password, string $hash) : bool;
}