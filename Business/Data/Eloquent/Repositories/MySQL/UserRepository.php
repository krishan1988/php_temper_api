<?php declare(strict_types = 1);

namespace Business\Data\Eloquent\Repositories\MySQL;

use Business\Data\Eloquent\Models\Users;
use Business\Domain\Boundary\Repositories\UserRepositoryInterface;
use Business\Domain\Entities\User;
use Mockery\Exception;

class UserRepository implements UserRepositoryInterface
{


    /**
     * @var MobileUser
     */
    private $users;

    /**
     * PeopleRepository constructor.
     *
     * @param MobileUser $mobileUser
     */
    public function __construct(Users $users)
    {

        $this->users = $users;
    }


    /**
     * @param array $credentials
     * @return User
     * @throws \Exception
     */
    public function getUser(string $username,string $password) : int
    {
           $password = md5($password);

        $sql = "SELECT id FROM users WHERE user_name = ? and password = ?";

        $data = app('db')->connection('mysql')->select($sql, [$username,$password]);

        if(!isset($data) || empty($data)){
            return 0;
        }

        return (int)$data[0]->id;

    }

     public function updateToken(string $username,string $token) : bool
    {
           return (bool) $this->users
            ->where('user_name', '=', $username)
            ->update(
                [
                    'token'        => $token
                ]
            );
    
    }

    public function checkToken(string $token) : int
    {

        $sql = "SELECT id FROM users WHERE token = ?";

        $data = app('db')->connection('mysql')->select($sql, [$token]);

        if(!isset($data) || empty($data)){
            return 0;
        }

        return (int)$data[0]->id;

    }


}