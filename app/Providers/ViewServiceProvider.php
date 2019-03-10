<?php

namespace App\Providers;

use App\Views\View;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        View::class,
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

        $container->share(View::class, function () {
            $loader = new \Twig\Loader\FilesystemLoader(base_path('views'));
            $twig = new \Twig\Environment($loader, [
                'cache' => false,
            ]);

            return new View($twig);
        });
    }
}
