<?php

namespace App\Providers;

use App\Config\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'config',
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

        $container->share('config', function () {
            $loader = new \App\Config\Loaders\ArrayLoader([
                'app' => base_path('config/app.php'),
                'cache' => base_path('config/cache.php'),
                'providers' => base_path('config/providers.php'),
                'db' => base_path('config/db.php'),
            ]);

            $config = (new Config())->load([$loader]);
            return $config;
        });
    }
}
