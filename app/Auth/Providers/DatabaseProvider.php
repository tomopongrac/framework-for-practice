<?php

declare(strict_types=1);

namespace App\Auth\Providers;

use App\Models\User;
use Doctrine\ORM\EntityManager;

class DatabaseProvider implements UserProvider
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * DatabaseProvider constructor.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get user by username
     *
     * @param  string  $username
     * @return User|null
     */
    public function getByUsername(string $username): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $username,
        ]);
    }

    /**
     * Get user by id
     *
     * @param  int  $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    /**
     * Get user by remember identifier
     *
     * @param  string  $identifier
     * @return User|null
     */
    public function getByRememberIdentifier(string $identifier): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy([
            'remember_identifier' => $identifier,
        ]);
    }

    /**
     * Erase user remember_identifier and remember token
     *
     * @param  int  $userId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function clearUserRememberToken(int $userId): void
    {
        $this->getById($userId)->update([
            'remember_identifier' => null,
            'remember_token' => null,
        ]);
        $this->entityManager->flush();
    }

    /**
     * Setting cookie with identifier token
     * and updating user row in database with identifier and hashed token
     *
     * @param  int  $userId
     * @param  string  $identifier
     * @param  string  $hash
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setUserRememberToken(int $userId, string $identifier, string $hash): void
    {
        $this->getById($userId)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $hash,
        ]);
        $this->entityManager->flush();
    }
}
