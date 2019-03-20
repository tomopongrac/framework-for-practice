<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Views\View;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
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
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }
}
