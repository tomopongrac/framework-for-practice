<?php

declare(strict_types=1);

namespace App\Security;

class TokenGenerator
{
    /**
     * Return generated string which will be used as a token.
     *
     * @return string
     * @throws \Exception
     */
    public function make(): string
    {
        return bin2hex(random_bytes(32));
    }
}