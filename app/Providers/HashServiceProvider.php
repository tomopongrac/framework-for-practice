<?php

namespace App\Providers;

use App\Auth\Hashing\BcryptHasher;
use App\Auth\Hashing\HasherInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class HashServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        HasherInterface::class,
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

        $container->share(HasherInterface::class, function () {
            return new BcryptHasher();
        });
    }
}
