<?php declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Business\Domain\Boundary\Adapters\DateTimeAdapter;
use Business\Domain\Boundary\Repositories\UserRepositoryInterface as UserRepository;
use Business\Domain\Entities\User;

class AuthMiddleware
{
 
    /**
     * @var DateTimeAdapter
     */
    private $dateTimeAdapter;


    private $userRepository;




    /**
     * AuthMiddleware constructor.
     *
     * @param DateTimeAdapter $dateTimeAdapter
     * @param Session $session
     * @param AuthTokenRepository $authTokenRepository
     */
    public function __construct(DateTimeAdapter $dateTimeAdapter,
                                UserRepository $userRepository
    )
    {
        $this->dateTimeAdapter = $dateTimeAdapter;
        $this->userRepository = $userRepository;
    }


    public function handle($request, Closure $next)
    {
        $token = '';

        // by default check whether the token is sent in the request header
        if($request->hasHeader('Authorization'))
        {
            $tokenParts = explode(' ', $request->header('Authorization'));

            if($tokenParts[0] != 'Bearer')
            {
                throw new \Exception("Token is not of the type 'Bearer'");
            }

            if(!isset($tokenParts[1]) || $tokenParts[1] === '')
            {
                throw new \Exception("Need a token");
            }

            $token = $tokenParts[1];
        }
        else
        {
            // otherwise check to see whether the token is sent in the request query
            $token = $request->get('token');

            if(is_null($token) || $token === '')
            {
                throw new \Exception("Need a token");
            }
        }

        $this->validateToken($token);

        // add to a global session object
        $tokenData['token'] = $token;

        return $next($request);
    }


// _____________________________________________________________________________________________________________ Private

    /**
     * Validate the token.
     *
     * @param array $tokenData
     * @throws \Exception
     */
    private function validateToken(string $tokenData) : void
    {
    

        if(!$this->isTokenExpired($tokenData))
        {
        

            throw new \Exception("The token is Invalid");
        }
    }




    /**
     * Check whether the token is expired.
     *
     * @param array $tokenData
     * @return bool
     */
    private function isTokenExpired(string $tokenData) : bool
    {
        $token = $this->userRepository->checkToken($tokenData);

        if($token != 0)
        {
            return true;
        }

        return false;
    }
}
