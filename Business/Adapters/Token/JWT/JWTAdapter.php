<?php declare(strict_types = 1);

namespace Business\Adapters\Token\JWT;

use Business\Domain\Boundary\Adapters\TokenAdapter;
use Business\Domain\Boundary\Adapters\DateTimeAdapter;
use Firebase\JWT\JWT;

class JWTAdapter implements TokenAdapter
{
    private $secretKey;
    private $issuer;
    private $lifetime;

    /**
     * @var DateTimeAdapter
     */
    private $dateTimeAdapter;


    /**
     * JWTAdapter constructor.
     *
     * @param array $config
     * @param DateTimeAdapter $dateTimeAdapter
     */
    public function __construct(array $config,
                                DateTimeAdapter $dateTimeAdapter
    )
    {
        $this->setConfig($config);
        $this->dateTimeAdapter = $dateTimeAdapter;
    }


    /**
     * Create an authentication token.
     *
     * @param int $id
     * @param array $payload
     * @return string
     */
    public function createToken(int $id, array $payload = []) : string
    {
        $currentTimestamp = $this->dateTimeAdapter->getInstance()->getTimestamp();

        // create token
        $tokenData = [
            'iss' => $this->issuer,                       // issuer
            'sub' => $id,                                 // subject
            'jti' => $id . $currentTimestamp,             // token id
            'iat' => $currentTimestamp,                   // issued at
            'exp' => $currentTimestamp + $this->lifetime, // expire on
        ];

        if(!empty($payload))
        {
            $tokenData['data'] = $payload;
        }

        return JWT::encode($tokenData, $this->secretKey);
    }


    /**
     * Decode a token and get its entire content.
     *
     * @param string $token
     * @return array
     */
    public function decodeToken(string $token) : array
    {
        $tokenData = [];

        if(empty($token))
        {
            JWTAdapterException::noToken();
        }

        try
        {
            $tokenData = JWT::decode($token, $this->secretKey, ['HS256']);
            $tokenData = json_decode(json_encode($tokenData), true);
        }
        catch (\Exception $e)
        {
            JWTAdapterException::invalidToken();
        }

        if(!isset($tokenData['jti']))
        {
            JWTAdapterException::noTokenId();
        }

        return $tokenData;
    }


    /**
     * Decode a token and get the token payload.
     *
     * @param string $token
     * @return array
     */
    public function getTokenPayload(string $token) : array
    {
        $tokenData = $this->decodeToken($token);

        if(isset($tokenData['data']))
        {
            return $tokenData['data'];
        }

        return [];
    }


    /**
     * Return user id of the token.
     *
     * @param string $token
     * @return int
     */
    public function getUserId(string $token) : int
    {
        return (int)$this->decodeToken($token)['sub'];
    }


// _____________________________________________________________________________________________________________ Private

    /**
     * Set configuration values.
     *
     * @param array $config
     */
    private function setConfig(array $config) : void
    {
        $this->secretKey = isset($config['token_secret_key'])
                            ? $config['token_secret_key'] : JWTAdapterException::noConfig();

        $this->issuer = isset($config['app_name'])
                            ? $config['app_name'] : JWTAdapterException::noConfig();

        $this->lifetime = isset($config['token_lifespan'])
                            ? (int)$config['token_lifespan'] : JWTAdapterException::noConfig();

        if($this->lifetime <= 0)
        {
            JWTAdapterException::invalidLifetimeValue();
        }
    }

}