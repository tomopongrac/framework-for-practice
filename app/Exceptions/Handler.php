<?php

namespace App\Exceptions;

use Exception;
use ReflectionClass;
use App\Exceptions\ValiadationException;

class Handler
{
    protected $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function respond()
    {
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (method_exists($this, $method = "handle{$class}")) {
            return $this->{$method}($this->exception);
        }

        return $this->unhandledException($this->exception);
    }

    protected function handleValidationException(ValidationException $e)
    {
        return redirect($e->getPath());
    }

    protected function unhandledException(Exception $e)
    {
        throw $e;
    }
}
