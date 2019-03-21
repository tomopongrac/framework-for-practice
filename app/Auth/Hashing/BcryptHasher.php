<?php

namespace App\Auth\Hashing;

use http\Exception\RuntimeException;

class BcryptHasher implements HasherInterface
{

    public function create($plain)
    {
        $hash = password_hash($plain, PASSWORD_BCRYPT, $this->options());

        if (!$hash) {
            throw new RuntimeException('Bcrypt is not supported.');
        }

        return $hash;
    }

    public function check($plain, $hash)
    {
        return password_verify($plain, $hash);
    }

    public function needsRehash($hash)
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, $this->options());
    }

    private function options()
    {
        return [
            'costs' => 12,
        ];
    }
}