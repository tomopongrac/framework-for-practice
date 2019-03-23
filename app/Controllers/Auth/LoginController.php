<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Views\View;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    protected $view;

    protected $auth;

    public function __construct(View $view, Auth $auth)
    {
        $this->view = $view;
        $this->auth = $auth;
    }

    public function index()
    {
        return $this->view->render('auth/login.twig');
    }

    public function store(ServerRequestInterface $request)
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $attempt = $this->auth->attempt($data['email'], $data['password']);

        if (!$attempt) {
            dump('failed');
            die();
        }

        return redirect('/');
    }
}
