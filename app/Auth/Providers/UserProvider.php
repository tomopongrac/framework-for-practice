<?php

declare(strict_types=1);

namespace App\Auth\Providers;

use App\Models\User;

interface UserProvider
{
    /**
     * Get user by username
     *
     * @param  string  $username
     * @return User|null
     */
    public function getByUsername(string $username): ?User;

    /**
     * Get user by id
     *
     * @param  int  $id
     * @return User|null
     */
    public function getById(int $id): ?User;

    /**
     * Get user by remember identifier
     *
     * @param  string  $identifier
     * @return User|null
     */
    public function getByRememberIdentifier(string $identifier): ?User;

    /**
     * Erase user remember_identifier and remember token
     *
     * @param  int  $id
     */
    public function clearUserRememberToken(int $id): void;

    /**
     * Setting cookie with identifier token
     * and updating user row in database with identifier and hashed token
     *
     * @param  int  $userId
     * @param  string  $identifier
     * @param  string  $hash
     */
    public function setUserRememberToken(int $userId, string $identifier, string $hash): void;
}
