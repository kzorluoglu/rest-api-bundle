<?php

namespace kzorluoglu\RestApiBundle\Interfaces;

interface LoginServiceInterface
{
    public function login(string $username, string $password): bool;

    public function getUserId(): string;
}
