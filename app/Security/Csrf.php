<?php

namespace App\Security;

use App\Session\SessionStoreInterface;

class Csrf
{
    protected $session;

    protected $persistToken = true;

    protected $tokenGenerator;

    public function __construct(SessionStoreInterface $session, TokenGenerator $tokenGenerator)
    {
        $this->session = $session;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function key()
    {
        return '_token';
    }

    public function token()
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

    protected function tokenNeedsToBeGenerated()
    {
        if (!$this->session->exists($this->key())) {
            return true;
        }

        if ($this->shouldPersistToken()) {
            return false;
        }

        return $this->session->exists($this->key());
    }

    protected function shouldPersistToken()
    {
        return $this->persistToken;
    }

    protected function getTokenFromSession()
    {
        return $this->session->get($this->key());
    }
}
