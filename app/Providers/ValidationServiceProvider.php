<?php

namespace App\Providers;

use App\Auth\Hashing\BcryptHasher;
use App\Rules\ExistsRule;
use Doctrine\ORM\EntityManager;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Valitron\Validator;

class ValidationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = [
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
        //
    }


    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     */
    public function boot()
    {
        Validator::addRule('exists', function ($field, $value, $params, $fields) {
            return (new ExistsRule($this->getContainer()->get(EntityManager::class)))->validate($field, $value, $params, $fields);
        }, 'is already in use');
    }
}
