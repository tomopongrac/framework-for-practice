<?php

namespace App\Providers;

use App\Session\Flash;
use App\Session\SessionStoreInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class FlashServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Flash::class,
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

        $container->share(Flash::class, function () use ($container) {
            return new Flash(
                $container->get(SessionStoreInterface::class)
            );
        });
    }
}
