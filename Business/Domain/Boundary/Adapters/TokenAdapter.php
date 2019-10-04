<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Adapters;

use Business\Domain\Boundary\Adapters\DateTimeAdapter;

interface TokenAdapter
{
    /**
     * TokenAdapter constructor.
     *
     * @param array $config
     * @param DateTimeAdapter $dateTimeAdapter
     */
    public function __construct(array $config, DateTimeAdapter $dateTimeAdapter);

    /**
     * Create an authentication token.
     *
     * @param int $id
     * @param array $payload
     * @return string
     */
    public function createToken(int $id, array $payload = []) : string;

    /**
     * Decode a token and get its entire content.
     *
     * @param string $token
     * @return array
     */
    public function decodeToken(string $token) : array;

    /**
     * Decode a token and get the token payload.
     *
     * @param string $token
     * @return array
     */
    public function getTokenPayload(string $token) : array;
}