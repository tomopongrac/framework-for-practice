<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use Psr\Http\Message\ServerRequestInterface;

class LogoutController extends Controller
{
    /**
     * @var Auth
     */
    protected $auth;

    /**
     * LogoutController constructor.
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function logout(ServerRequestInterface $request)
    {
        $this->auth->logout();

        return redirect('/');
    }
}
