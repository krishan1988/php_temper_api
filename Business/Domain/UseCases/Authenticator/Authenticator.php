<?php declare(strict_types = 1);

namespace Business\Domain\UseCases\Authenticator;

use Business\Domain\Boundary\Repositories\PeopleRepositoryInterface as UserRepository;
use Business\Domain\Boundary\Globals\Session;
use Business\Domain\Boundary\Adapters\TokenAdapter;
use Business\Domain\Boundary\Adapters\HashAdapter;
use Business\Domain\Entities\User;
use Business\Domain\Boundary\Repositories\AuthTokenRepositoryInterface as AuthTokenRepository;
use Business\Adapters\Hash\Bcrypt\BcryptAdapter;

class Authenticator
{
    /**
     * @var TokenAdapter
     */
    private $tokenAdapter;

    /**
     * @var HashAdapter
     */
    private $hashAdapter;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var AuthTokensRepository
     */
    private $authTokenRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var BcryptAdapter
     */
    private $bcryptAdapter;


    /**
     * Authenticator constructor.
     *
     * @param TokenAdapter $tokenAdapter
     * @param HashAdapter $hashAdapter
     * @param Session $session
     * @param AuthTokenRepository $authTokenRepository
     * @param UserRepository $userRepository
     * @param BcryptAdapter $bcryptAdapter
     */
    public function __construct(TokenAdapter $tokenAdapter,
                                HashAdapter $hashAdapter,
                                Session $session,
                                AuthTokenRepository $authTokenRepository,
                                UserRepository $userRepository,
                                BcryptAdapter $bcryptAdapter
    )
    {
        $this->tokenAdapter = $tokenAdapter;
        $this->hashAdapter = $hashAdapter;
        $this->session = $session;
        $this->authTokenRepository = $authTokenRepository;
        $this->userRepository = $userRepository;
        $this->bcryptAdapter = $bcryptAdapter;
    }


    /**
     * Login user.
     *
     * @param array $credentials
     * @return string
     * @throws AuthenticatorException
     */
    public function login(array $credentials) : string
    {
        $user = $this->userRepository->getUser($credentials);

        $password = trim($credentials['password']);
        $passwordHash = $user->password;

        //if($this->bcryptAdapter->verify($password, $passwordHash)){ // not working

        if(md5($password) === $passwordHash){

            $this->validateUser($user);
            $token = $this->createToken($user);
            $this->persistToken($token);

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
        $this->authTokenRepository->deleteToken($this->session->getUserId(),
                                                $this->session->getTokenId());
    }


    /**
     * Create the token.
     *
     * @param User $user
     * @return string
     */
    private function createToken(User $user) : string
    {
        $id = $user->id;
        $payload = [
            "name" => $user->name,
            "role" => $user->role,
        ];

        return $this->tokenAdapter->createToken($id, $payload);
    }


    /**
     * Save the token in the persistence layer.
     *
     * @param string $token
     */
    private function persistToken(string $token) : void
    {
        $tokenData = $this->tokenAdapter->decodeToken($token);

        $this->authTokenRepository->saveToken($tokenData['sub'], $tokenData['jti'], $token);

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