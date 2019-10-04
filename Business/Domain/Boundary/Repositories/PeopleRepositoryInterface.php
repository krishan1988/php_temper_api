<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Repositories;
use Business\Domain\Entities\User;

interface PeopleRepositoryInterface
{
    /**
     * Get user by credentials.
     *
     * @param array $credentials
     * @return User
     */
    public function getUser(array $credentials) : User;


    /**
     * insert Mobile user details at the sign in with mobile
     *
     * @return User
     */
    public function insertPeople($data,$id = null);


    /**
     * @param $data
     * @param $id
     * @return mixed
     */
    public function insertUserComment($data,$id);

    /**
     * @param $lat
     * @param $lon
     * @return mixed
     */
    public function getUserComment($lat, $lon);

}