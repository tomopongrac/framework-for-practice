<?php

namespace App\Exceptions;

use App\Session\SessionStoreInterface;
use App\Views\View;
use Exception;
use ReflectionClass;
use App\Exceptions\ValiadationException;

class Handler
{
    protected $exception;

    protected $session;

    protected $view;

    public function __construct(Exception $exception, SessionStoreInterface $session, View $view)
    {
        $this->exception = $exception;
        $this->session = $session;
        $this->view = $view;
    }

    public function respond()
    {
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (method_exists($this, $method = "handle{$class}")) {
            return $this->{$method}($this->exception);
        }

        return $this->unhandledException($this->exception);
    }

    protected function handleCsrfTokenException(Exception $e)
    {
        return $this->view->render('errors/csrf.twig');
    }

    protected function handleValidationException(ValidationException $e)
    {
        $this->session->set('errors', $e->getErrors());
        $this->session->set('old', $e->getOldInput());

        return redirect($e->getPath());
    }

    protected function unhandledException(Exception $e)
    {
        throw $e;
    }
}
