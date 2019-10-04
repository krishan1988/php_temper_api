<?php declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Business\Domain\Boundary\Adapters\TokenAdapter;
use Business\Domain\Boundary\Adapters\DateTimeAdapter;
use Business\Domain\Boundary\Globals\Session;
use Business\Domain\Boundary\Repositories\AuthTokenRepositoryInterface as AuthTokenRepository;

class AuthMiddleware
{
    /**
     * @var TokenAdapter
     */
    private $tokenAdapter;

    /**
     * @var DateTimeAdapter
     */
    private $dateTimeAdapter;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var AuthTokenRepository
     */
    private $authTokenRepository;


    /**
     * AuthMiddleware constructor.
     *
     * @param TokenAdapter $tokenAdapter
     * @param DateTimeAdapter $dateTimeAdapter
     * @param Session $session
     * @param AuthTokenRepository $authTokenRepository
     */
    public function __construct(TokenAdapter $tokenAdapter,
                                DateTimeAdapter $dateTimeAdapter,
                                Session $session,
                                AuthTokenRepository $authTokenRepository
    )
    {
        $this->tokenAdapter = $tokenAdapter;
        $this->session = $session;
        $this->authTokenRepository = $authTokenRepository;
        $this->dateTimeAdapter = $dateTimeAdapter;
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

        $tokenData = $this->tokenAdapter->decodeToken($token);

        $this->validateToken($tokenData);

        // add to a global session object
        $tokenData['token'] = $token;
        $this->session->init($tokenData);

        return $next($request);
    }


// _____________________________________________________________________________________________________________ Private

    /**
     * Validate the token.
     *
     * @param array $tokenData
     * @throws \Exception
     */
    private function validateToken(array $tokenData) : void
    {
        if($this->isTokenBlacklisted($tokenData))
        {
            throw new \Exception("The token has been blacklisted");
        }

        if($this->isTokenExpired($tokenData))
        {
            // remove from persistence
            $this->authTokenRepository->deleteToken($tokenData['sub'], $tokenData['jti']);

            throw new \Exception("The token has expired");
        }
    }


    /**
     * Check whether the token has been blacklisted.
     *
     * @param array $tokenData
     * @return bool
     */
    private function isTokenBlacklisted(array $tokenData) : bool
    {
        return ! $this->authTokenRepository->isTokenExists($tokenData['sub'], $tokenData['jti']);
    }


    /**
     * Check whether the token is expired.
     *
     * @param array $tokenData
     * @return bool
     */
    private function isTokenExpired(array $tokenData) : bool
    {
        $currentTimestamp = $this->dateTimeAdapter->getInstance()->getTimestamp();

        if($currentTimestamp > (int)$tokenData['exp'])
        {
            return true;
        }

        return false;
    }
}
