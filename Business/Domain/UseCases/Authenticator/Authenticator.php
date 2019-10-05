<?php

namespace Business\Domain\UseCases\Authenticator;

use Business\Domain\Boundary\Repositories\UserRepositoryInterface as UserRepository;
use Business\Domain\Boundary\Adapters\HashAdapter;
use Business\Domain\Entities\User;

class Authenticator
{
 
    /**
     * @var HashAdapter
     */
    private $hashAdapter;

    /**
     * @var UserRepository
     */
    private $userRepository;
  


    /**
     * Authenticator constructor.
     *
     * @param UserRepository $userRepository
     * @param BcryptAdapter $bcryptAdapter
     */
    public function __construct(HashAdapter $hashAdapter,
                                UserRepository $userRepository
                        
    )
    {
        $this->hashAdapter = $hashAdapter;
        $this->userRepository = $userRepository;
    }


    /**
     * Login user.
     *
     * @param array $credentials
     * @return string
     * @throws AuthenticatorException
     */
    public function login(string $username, string $password) : string
    {
        $user = $this->userRepository->getUser($username,$password);


        if($user = 1){

            $token = $this->createToken($user,$username);
        

        }else{
             AuthenticatorException::invalidCredentials();
        }

        return $token;
    }


    /**
     * Logout user.
     */
    public function logout() : void
    {
       // $this->authTokenRepository->deleteToken($this->session->getUserId(),
                                               // $this->session->getTokenId());
    }


    /**
     * Create the token.
     *
     * @return string
     */
    private function createToken(int $user,string $username) : string
    {


        $key =  random_int(10, 9000000);

         $user = $this->userRepository->updateToken($username,$key);
         return $key ;
    }


    /**
     * Save the token in the persistence layer.
     *
     * @param string $token
     */
    private function persistToken(string $token) : void
    {
        //$tokenData = $this->tokenAdapter->decodeToken($token);

       // $this->authTokenRepository->saveToken($tokenData['sub'], $tokenData['jti'], $token);

        return;
    }


    /**
     * Validate user.
     *
     * @param User $user
     * @throws AuthenticatorException
     */
    private function validateUser(User $user) : void
    {
        if(is_null($user))
        {
            AuthenticatorException::invalidCredentials();
        }

        return;
    }


}