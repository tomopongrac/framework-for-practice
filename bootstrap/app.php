<?php

session_start();

require_once __DIR__.'/../vendor/autoload.php';

try {
    if (isset($environmentEnv)) {
        $dotenvFile = '.env.'.$environmentEnv;
    } else {
        $dotenvFile = '.env';
    }

    $dotenv = \Dotenv\Dotenv::create(base_path(), $dotenvFile);
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
}

require_once base_path('bootstrap/container.php');

$route = $container->get(\League\Route\Router::class);

require_once base_path('bootstrap/middleware.php');

require_once base_path('routes/web.php');

try {
    $response = $route->dispatch(
        $container->get('request')
    );
} catch (Exception $e) {
    $handler = new \App\Exceptions\Handler(
        $e,
        $container->get(\App\Session\SessionStoreInterface::class)
    );
    $response = $handler->respond();
}
