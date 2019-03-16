<?php

$container = new \League\Container\Container();

// register the reflection container as a delegate to enable auto wiring
$container->delegate(
    new League\Container\ReflectionContainer
);

$container->addServiceProvider(new \App\Providers\ConfigServiceProvider());

foreach ($container->get('config')->get('providers') as $provider) {
    $container->addServiceProvider($provider);
}
