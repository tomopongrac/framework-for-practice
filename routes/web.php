<?php

use Psr\Http\Message\ServerRequestInterface;

$route->get('/dashboard', 'App\Controllers\DashboardController::index')->setName('dashboard');

$route->get('/', 'App\Controllers\HomeController::index')->setName('homepage');
$route->get('/auth/signin', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
$route->post('/auth/signin', 'App\Controllers\Auth\LoginController::store');

$route->post('/auth/logout', 'App\Controllers\Auth\LogoutController::logout')->setName('auth.logout');

$route->get('/auth/register', 'App\Controllers\Auth\RegisterController::index')->setName('auth.register');
$route->post('/auth/register', 'App\Controllers\Auth\RegisterController::register');
