<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class AppServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Router::class,
        'request',
        'emitter',
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

        $container->share(Router::class, function () use ($container) {
            $strategy = (new ApplicationStrategy)->setContainer($container);
            return (new Router())->setStrategy($strategy);
        });

        $container->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });

        $container->share('emitter', SapiEmitter::class);
    }
}
