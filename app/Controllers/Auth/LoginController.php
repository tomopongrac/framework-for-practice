<?php

namespace App\Controllers\Auth;

use App\Views\View;

class LoginController
{
    protected $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index()
    {
        return $this->view->render('auth/login.twig');
    }

    public function store(ServerRequestInterface $request)
    {
    }
}
