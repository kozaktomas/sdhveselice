<?php

namespace App;

use Nette;


final class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /**
     * UserManager constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }


    /**
     * Performs an authentication.
     * @param array $credentials
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        if ($username === $this->username && $password === $this->password) {
            return new Nette\Security\Identity(1, 'admin', []);
        } else {
            throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        }
    }


}
