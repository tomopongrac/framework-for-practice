<?php

use Psr\Http\Message\ServerRequestInterface;

$route->get('/', 'App\Controllers\HomeController::index')->setName('homepage“');
