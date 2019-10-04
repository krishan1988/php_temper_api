<?php declare(strict_types = 1);

namespace Business\Adapters\Hash\Bcrypt;

use Business\Domain\Boundary\Adapters\HashAdapter;

class BcryptAdapter implements HashAdapter
{
    private $options = [];

    /**
     * HashAdapter constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }


    /**
     * Hash a string.
     *
     * @param string $string
     * @return string
     */
    public function hash(string $string) : string
    {
        return password_hash($string, PASSWORD_BCRYPT, $this->options);
    }


    /**
     * Verify the password
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verify(string $password, string $hash) : bool
    {
        return password_verify($password, $hash);
    }


// _____________________________________________________________________________________________________________ Private

    /**
     * Set configuration values.
     *
     * @param array $config
     */
    private function setConfig(array $config) : void
    {

    }

}