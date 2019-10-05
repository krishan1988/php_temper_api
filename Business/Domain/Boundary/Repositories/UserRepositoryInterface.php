<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Repositories;
use Business\Domain\Entities\User;

interface UserRepositoryInterface
{
    /**
     * Get user by credentials.
     *
     * @param array $credentials
     * @return User
     */
    public function getUser(string $username,string $password) : int;

    public function updateToken(string $username,string $token) : bool;

     public function checkToken(string $token) : int;


}