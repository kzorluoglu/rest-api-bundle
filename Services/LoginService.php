<?php


namespace kzorluoglu\RestApiBundle\Services;

use kzorluoglu\RestApiBundle\Interfaces\LoginServiceInterface;
use TdbDataExtranetUser;

class LoginService implements LoginServiceInterface
{

    private TdbDataExtranetUser $user;

    public function __construct(TdbDataExtranetUser $user)
    {
        $this->user = $user;
    }

    public function login(string $username, string $password): bool
    {
        return $this->user->Login($username, $password);
    }

    public function getUserId(): string
    {
        if (false === $this->user->IsLoggedIn())
        {
            throw new \Exception('Please try logging in.');
        }

        return $this->user->id;
    }
}
