<?php declare(strict_types = 1);

namespace Business\Data\Eloquent\Repositories\MySQL;

use Business\Domain\Boundary\Repositories\AuthTokenRepositoryInterface;
use Business\Data\Eloquent\Models\Users;

class AuthTokenRepository implements AuthTokenRepositoryInterface
{
    /**
     * @var AuthTokenRepository
     */
    private $authToken;


    /**
     * AuthTokenRepository constructor.
     *
     * @param AuthTokenRepository $authToken
     */
    public function __construct(AuthToken $authToken)
    {
        $this->authToken = $authToken;
    }


    /**
     * Save the token.
     *
     * @param int $id
     * @param string $tokenId
     * @param string $token
     */
    public function saveToken(int $id, string $tokenId, string $token) : void
    {
        $driverToken = $this->authToken->where('user_id', '=', $id)->first();

        if($driverToken !== null)
        {
            $driverToken->delete();
        }

        $record = [
            'user_id' => $id,
            'token_id' => $tokenId,
            'token' => $token
        ];

        $this->authToken->create($record);
    }


    /**
     * Check whether token exists.
     *
     * @param int $id
     * @param string $tokenId
     * @return bool
     */
    public function isTokenExists(int $id, string $tokenId) : bool
    {
        return $this->authToken->where('user_id', '=', $id)
                                ->where('token_id', '=', $tokenId)
                                ->exists();
    }


    /**
     * Delete the token.
     *
     * @param int $id
     * @param string $tokenId
     */
    public function deleteToken(int $id, string $tokenId) : void
    {
        $driverToken = $this->authToken->where('user_id', '=', $id)
                        ->where('token_id', '=', $tokenId)
                        ->first();

        if($driverToken !== null)
        {
            $driverToken->delete();
        }
    }

}