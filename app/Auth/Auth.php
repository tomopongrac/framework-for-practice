<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Hashing\HasherInterface;
use App\Cookie\CookieJar;
use App\Models\User;
use App\Session\SessionStoreInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;

class Auth
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var HasherInterface
     */
    protected $hasher;

    /**
     * @var SessionStoreInterface
     */
    protected $session;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Recaller
     */
    protected $recaller;

    /**
     * @var CookieJar
     */
    protected $cookie;

    /**
     * Auth constructor.
     *
     * @param  EntityManager  $entityManager
     * @param  HasherInterface  $hasher
     * @param  SessionStoreInterface  $session
     * @param  Recaller  $recaller
     * @param  CookieJar  $cookie
     */
    public function __construct(
        EntityManager $entityManager,
        HasherInterface $hasher,
        SessionStoreInterface $session,
        Recaller $recaller,
        CookieJar $cookie
    ) {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
    }

    /**
     * Delete session with key "id" to logout user
     */
    public function logout(): void
    {
        $this->session->clear(['id']);
    }

    /**
     * Attempt to login user
     *
     * @param  string  $username
     * @param  string  $password
     * @param  bool  $remember
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function attempt(string $username, string $password, bool $remember = false): bool
    {
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }

    /**
     * Returnig user object
     *
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * Checking if in session exist key id
     *
     * @return bool
     */
    public function check(): bool
    {
        return $this->hasUserInSession();
    }

    /**
     * Checking if in session exist key id
     *
     * @return bool
     */
    public function hasUserInSession(): bool
    {
        return $this->session->exists('id');
    }

    /**
     * Setting user property from session id
     *
     * @throws Exception
     */
    public function setUserFromSession(): void
    {
        $user = $this->getById($this->session->get('id'));

        if (!$user) {
            throw new Exception();
        }

        $this->user = $user;
    }

    /**
     * Setting user property from session id
     *
     * @throws Exception
     */
    public function setUserFromCookie(): void
    {
        list($identifier, $token) = $this->recaller->splitCookieValue(
            $this->cookie->get('remember')
        );

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'remember_identifier' => $identifier,
        ]);

        if (!$user) {
            $this->cookie->clear('remember');

            throw new Exception();
        }

        if (!$this->recaller->validateToken($token, $user->remember_token)) {
            $this->getById($user->id)->update([
                'remember_identifier' => null,
                'remember_token' => null,
            ]);
            $this->entityManager->flush();
            $this->cookie->clear('remember'); 

            throw new Exception();
        }

        $this->setUserSession($user);

        $this->user = $user;
    }

    /**
     * Check if user has "remember me" cookie
     *
     * @return bool
     */
    public function hasRecaller(): bool
    {
        return $this->cookie->exists('remember');
    }

    /**
     * Check if password is correct
     *
     * @param  User  $user
     * @param  string  $password
     * @return bool
     */
    protected function hasValidCredentials(User $user, string $password): bool
    {
        return $this->hasher->check($password, $user->password);
    }

    /**
     * Get user by id
     *
     * @param  int  $id
     * @return User|null
     */
    protected function getById(int $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    /**
     * Get user by username
     *
     * @param  string  $username
     * @return User|null
     */
    protected function getByUsername(string $username): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $username,
        ]);
    }

    /**
     * Setting a session with user id
     *
     * @param  User  $user
     */
    protected function setUserSession(User $user): void
    {
        $this->session->set('id', $user->id);
    }

    /**
     * Setting cookie with identifier token
     * and updating user row in database with identifier and hashed token
     *
     * @param  User  $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function setRememberToken(User $user): void
    {
        list($identifier, $token) = $this->recaller->generate();

        $this->cookie->set('remember', $this->recaller->generateValueForCookie($identifier, $token));

        $this->getById($user->id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $this->recaller->getTokenHasForDatabase($token),
        ]);
        $this->entityManager->flush();
    }

}
