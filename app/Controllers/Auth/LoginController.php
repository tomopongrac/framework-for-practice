<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Session\Flash;
use App\Views\View;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    protected $view;

    protected $auth;

    protected $flash;

    public function __construct(View $view, Auth $auth, Flash $flash)
    {
        $this->view = $view;
        $this->auth = $auth;
        $this->flash = $flash;
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

        $attempt = $this->auth->attempt($data['email'], $data['password'], isset($data['remember']));

        if (!$attempt) {
            $this->flash->now('error', 'Could not sign you in with those details.');

            return redirect($request->getUri()->getPath());
        }

        return redirect('/');
    }
}
