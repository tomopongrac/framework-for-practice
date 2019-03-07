<?php

use Psr\Http\Message\ServerRequestInterface;

$route->get('/', function (ServerRequestInterface $request) {
    $response = new \Zend\Diactoros\Response();
    $response->getBody()->write('<h1>Framework for practice</h1>');

    return $response;
});
