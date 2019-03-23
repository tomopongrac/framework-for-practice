<?php

namespace App\Auth;

use App\Auth\Hashing\HasherInterface;
use App\Models\User;
use App\Session\SessionStoreInterface;
use Doctrine\ORM\EntityManager;

class Auth
{
    protected $entityManager;

    protected $hasher;

    protected $session;

    public function __construct(EntityManager $entityManager, HasherInterface $hasher, SessionStoreInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->session = $session;
    }

    public function attempt($username, $password)
    {
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        $this->setUserSession($user);

        return true;
    }

    protected function hasValidCredentials($user, $password)
    {
        return $this->hasher->check($password, $user->password);
    }

    protected function getByUsername($username)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $username
        ]);
    }

    protected function setUserSession($user)
    {
        $this->session->set('id', $user->id);
    }

}
