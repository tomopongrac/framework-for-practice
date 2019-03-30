<?php

namespace App\Security;

class TokenGenerator
{
    public function make()
    {
        return bin2hex(random_bytes(32));
    }
}