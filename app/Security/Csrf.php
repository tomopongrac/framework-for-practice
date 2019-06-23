<?php

declare(strict_types=1);

namespace App\Security;

use App\Session\SessionStoreInterface;

class Csrf
{
    /**
     * @var SessionStoreInterface
     */
    protected $session;

    /**
     * @var bool
     */
    protected $persistToken = true;

    /**
     * @var TokenGenerator
     */
    protected $tokenGenerator;

    public function __construct(SessionStoreInterface $session, TokenGenerator $tokenGenerator)
    {
        $this->session = $session;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * Token name
     *
     * @return string
     */
    public function key(): string
    {
        return '_token';
    }

    /**
     * Set the session with token and return token string.
     *
     * @return string
     */
    public function token(): string
    {
        if (!$this->tokenNeedsToBeGenerated()) {
            return $this->getTokenFromSession();
        }

        $this->session->set(
            $this->key(),
            $token = $this->tokenGenerator->make()
        );

        return $token;
    }

    /**
     * Check if token string is equal with token from session.
     *
     * @param  string  $token
     * @return bool
     */
    public function tokenIsValid(string $token): bool
    {
        return $token == $this->session->get($this->key());
    }

    /**
     * Determinate if token needs to be generated.
     *
     * @return bool
     */
    protected function tokenNeedsToBeGenerated(): bool
    {
        if (!$this->session->exists($this->key())) {
            return true;
        }

        if ($this->shouldPersistToken()) {
            return false;
        }

        return $this->session->exists($this->key());
    }

    /**
     * Return value should the token needs to persist
     *
     * @return bool
     */
    protected function shouldPersistToken(): bool
    {
        return $this->persistToken;
    }

    /**
     * Get token value from session.
     *
     * @return string
     */
    protected function getTokenFromSession(): string
    {
        return $this->session->get($this->key());
    }
}
