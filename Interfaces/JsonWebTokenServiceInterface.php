<?php

namespace kzorluoglu\RestApiBundle\Interfaces;

interface JsonWebTokenServiceInterface
{
    /**
     * Creates a transfer token for the given user id that expires after
     * the given number of seconds. The returned token must be safe for
     * usage in URLs as is.
     */
    public function createTokenForUser(string $userId, int $expiresAfterSeconds): string;

    /**
     * Validates the given token as created by {@see createTokenForUser}.
     * The method must return `null` if the token is invalid or the user id contained
     * in the token if it is valid. If the expiry time of a token is over, then
     * the token must be invalid.
     */
    public function validateToken(string $token): ?string;
}
