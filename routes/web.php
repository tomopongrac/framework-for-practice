<?php

use Psr\Http\Message\ServerRequestInterface;

$route->get('/', 'App\Controllers\HomeController::index')->setName('homepage');
$route->get('/auth/signin', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
$route->post('/auth/signin', 'App\Controllers\Auth\LoginController::store');
