<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Hashing\HasherInterface;
use App\Auth\Providers\UserProvider;
use App\Cookie\CookieJar;
use App\Models\User;
use App\Session\SessionStoreInterface;
use Exception;

class Auth
{
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
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * Auth constructor.
     *
     * @param  HasherInterface  $hasher
     * @param  SessionStoreInterface  $session
     * @param  Recaller  $recaller
     * @param  CookieJar  $cookie
     */
    public function __construct(
        HasherInterface $hasher,
        SessionStoreInterface $session,
        Recaller $recaller,
        CookieJar $cookie,
        UserProvider $userProvider
    ) {
        $this->hasher = $hasher;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
        $this->userProvider = $userProvider;
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
     * @throws Exception
     */
    public function attempt(string $username, string $password, bool $remember = false): bool
    {
        $user = $this->userProvider->getByUsername($username);

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
        $user = $this->userProvider->getById($this->session->get('id'));

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

        $user = $this->userProvider->getByRememberIdentifier($identifier);

        if (!$user) {
            $this->cookie->clear('remember');

            throw new Exception();
        }

        if (!$this->recaller->validateToken($token, $user->remember_token)) {
            $this->userProvider->clearUserRememberToken($user->id);
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
     * @throws Exception
     */
    protected function setRememberToken(User $user): void
    {
        list($identifier, $token) = $this->recaller->generate();

        $this->cookie->set('remember', $this->recaller->generateValueForCookie($identifier, $token));

        $this->userProvider->setUserRememberToken($user->id, $identifier,
            $this->recaller->getTokenHasForDatabase($token));
    }

}
