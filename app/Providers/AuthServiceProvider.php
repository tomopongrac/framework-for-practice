<?php

namespace App\Providers;

use App\Auth\Auth;
use App\Auth\Hashing\HasherInterface;
use App\Auth\Recaller;
use App\Cookie\CookieJar;
use App\Session\SessionStoreInterface;
use Doctrine\ORM\EntityManager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AuthServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Auth::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(Auth::class, function () use ($container) {
            return new Auth(
                $container->get(EntityManager::class),
                $container->get(HasherInterface::class),
                $container->get(SessionStoreInterface::class),
                new Recaller(),
                $container->get(CookieJar::class)
            );
        });
    }
}
