<?php

namespace App\Auth\Hashing;

use http\Exception\RuntimeException;

class BcryptHasher implements HasherInterface
{

    /**
     * Create hashed string from plain text
     *
     * @param  string  $plain
     * @return string
     */
    public function create(string $plain): string
    {
        $hash = password_hash($plain, PASSWORD_BCRYPT, $this->options());

        if (!$hash) {
            throw new RuntimeException('Bcrypt is not supported.');
        }

        return $hash;
    }

    /**
     * Checking if hashed plain text same as the hashed string which is saved to database
     *
     * @param  string  $plain
     * @param  string  $hash
     * @return boolean
     */
    public function check(string $plain, ?string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    /**
     * Check if hased value needs refresh
     * That is happening if value of costs is changed
     *
     * @param  string  $hash
     * @return bool
     */
    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, $this->options());
    }

    /**
     * Options for hashing function
     *
     * @return array
     */
    private function options(): array
    {
        return [
            'costs' => 12,
        ];
    }
}