<?php declare(strict_types = 1);

namespace Business\Globals;

use Business\Domain\Boundary\Globals\Session;

class RequestCycleSession implements Session
{
    private $token = "";
    private $userId = null;
    private $tokenId = "";
    private $payload = [];


    /**
     * Initialize session with a provided data array.
     *
     * @param array $data
     */
    public function init(array $data) : void
    {
        $this->token = $data['token'];
        $this->userId = (int)$data['sub'];
        $this->tokenId = $data['jti'];

        if(isset($data['data']))
        {
            $this->payload = (array)$data['data'];
        }
    }


    /**
     * Return the token.
     *
     * @return string
     */
    public function getToken() : string
    {
        return $this->token;
    }


    /**
     * Return the user id in token.
     *
     * @return int
     * @throws \Exception
     */
    public function getUserId() : int
    {
        if(is_null($this->userId))
        {
            throw new \Exception("No value is assigned to id");
        }

        return (int)$this->userId;
    }


    /**
     * Return the unique id assigned to token.
     *
     * @return string
     */
    public function getTokenId() : string
    {
        return $this->tokenId;
    }


    /**
     * Return the token payload.
     *
     * @return array
     */
    public function getPayload() : array
    {
        return $this->payload;
    }
}