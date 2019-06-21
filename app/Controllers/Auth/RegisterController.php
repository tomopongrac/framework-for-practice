<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Auth\Hashing\HasherInterface;
use App\Controllers\Controller;
use App\Models\User;
use App\Views\View;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface;

class RegisterController extends Controller
{

    /**
     * @var View
     */
    protected $view;

    /**
     * @var HasherInterface
     */
    protected $hash;

    /**
     * @var EntityManager
     */
    protected $entity;

    /**
     * @var Auth
     */
    protected $auth;

    public function __construct(View $view, HasherInterface $hash, EntityManager $entity, Auth $auth)
    {
        $this->view = $view;
        $this->hash = $hash;
        $this->entity = $entity;
        $this->auth = $auth;
    }

    public function index(ServerRequestInterface $request)
    {
        return $this->view->render('auth/register.twig');
    }

    public function register(ServerRequestInterface $request)
    {
        $data = $this->validateRegistration($request);

        $user = $this->createUser($data);

        if (!$this->auth->attempt($data['email'], $data['password'])) {
            return redirect('/');
        }

        return redirect('/');
    }

    protected function validateRegistration(ServerRequestInterface $request)
    {
        return $this->validate($request, [
            'email' => ['required', 'email', ['exists', User::class]],
            'name' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);
    }

    protected function createUser(array $data)
    {
        $user = new User();
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hash->create($data['password']),
        ]);

        $this->entity->persist($user);
        $this->entity->flush();

        return $user;
    }
}
