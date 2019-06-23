<?php

namespace App\Auth\Hashing;

interface HasherInterface
{
    /**
     * Create hashed string from plain text
     *
     * @param  string  $plain
     * @return string
     */
    public function create(string $plain): string;

    /**
     * Checking if hashed plain text same as the hashed string which is saved to database
     *
     * @param  string  $plain
     * @param  string  $hash
     * @return boolean
     */
    public function check(string $plain, ?string $hash): bool;

    /**
     * Check if hased value needs refresh
     * That is happening if value of costs is changed
     *
     * @param  string  $hash
     * @return bool
     */
    public function needsRehash(string $hash): bool;
}