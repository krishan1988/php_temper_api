<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Repositories;

interface AuthTokenRepositoryInterface
{
    /**
     * Save the token.
     *
     * @param int $id
     * @param string $tokenId
     * @param string $token
     */
    public function saveToken(int $id, string $tokenId, string $token) : void;

    /**
     * Check whether token exists.
     *
     * @param int $id
     * @param string $tokenId
     * @return bool
     */
    public function isTokenExists(int $id, string $tokenId) : bool;

    /**
     * @param int $id
     * @param string $tokenId
     */
    public function deleteToken(int $id, string $tokenId) : void;
}