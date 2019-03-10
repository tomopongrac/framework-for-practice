<?php

namespace App\Controllers;

use Zend\Diactoros\Response;

class HomeController
{
    public function index()
    {
        $response = new Response();
        $response->getBody()->write('<h1>Framework for practice</h1>');

        return $response;
    }
}
