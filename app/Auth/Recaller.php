<?php

declare(strict_types=1);

namespace App\Auth;

class Recaller
{
    /**
     * Separator for value which will be stored to cookie
     *
     * @var string
     */
    protected $separator = '|';

    /**
     * Returning array with identifier and token
     *
     * @return array
     * @throws \Exception
     */
    public function generate(): array
    {
        return [$this->generateIdentifier(), $this->generateToken()];
    }

    /**
     * Get indentifier and token from cookie value
     *
     * @param  string  $value
     * @return array
     */
    public function splitCookieValue(string $value): array
    {
        return explode($this->separator, $value);
    }
    /**
     * Generate value which will be stored in cookie for "remember me" feature
     *
     * @param  string  $identifier
     * @param  string  $token
     * @return string
     */
    public function generateValueForCookie(string $identifier, string $token): string
    {
        return $identifier.$this->separator.$token;
    }

    /**
     * Hash token which will be stored to database
     *
     * @param  string  $token
     * @return string
     */
    public function getTokenHasForDatabase(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Check if plain token is equal with hashed token
     *
     * @param  string  $plain
     * @param  string  $hash
     * @return bool
     * @throws \Exception
     */
    public function validateToken(string $plain, string $hash): bool
    {
        return $this->getTokenHasForDatabase($plain) === $hash;
    }

    /**
     * Generate string which will be identifier
     *
     * @return string
     * @throws \Exception
     */
    protected function generateIdentifier(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Generate string which will be token
     *
     * @return string
     * @throws \Exception
     */
    protected function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
