<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Globals;

interface Session
{
    /**
     * Initialize session with a provided data array.
     *
     * @param array $data
     */
    public function init(array $data) : void;


    /**
     * Return the token.
     *
     * @return string
     */
    public function getToken() : string;


    /**
     * Return the user id in token.
     *
     * @return int
     */
    public function getUserId() : int;


    /**
     * Return the unique id assigned to token.
     *
     * @return string
     */
    public function getTokenId() : string;


    /**
     * Return the token payload.
     *
     * @return array
     */
    public function getPayload() : array;



}